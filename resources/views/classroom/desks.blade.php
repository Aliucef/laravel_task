@extends('layouts.app')

@section('header')
    <h2 class="text-2xl font-semibold leading-tight text-gray-800 dark:text-gray-200">{{ $classroom->name }} Desk Layout</h2>
@endsection

@section('content')
@if (session('success'))
<div id="success-message" class="mb-4 bg-green-500 text-white p-2 rounded" style="background: transparent">
    {{ session('success') }}
</div>
@endif
<center>
    <div style="background: #999783;width:60%">
        <h1>
            <center> <img height="20%" width="20%" src="{{ asset('storage/photos/board2.png') }}" alt=""></center>
            </h1>
    </div>
</center>



    <div class="desk-table-container">
        <table class="desk-table">
            @for ($i = 1; $i <= $rows; $i++)
                <tr>
                    @for ($j = 1; $j <= $cols; $j++)
                        @php
                            $deskNumber = ($i - 1) * $cols + $j;
                            $isDouble = $classroom->seat_type == 'double';
                            $student1 = $students->firstWhere('seat_number', $deskNumber * 2 - 1);
                            $student2 = $students->firstWhere('seat_number', $deskNumber * 2);
                        @endphp
                        <td class="desk" data-desk-number="{{ $deskNumber }}">
                            <div class="desk-wrapper">
                                @if ($isDouble)
                                    <img src="{{ asset('storage/photos/double.png') }}" alt="Desk {{ $deskNumber }}" class="desk-img">
                                    <div class="student-wrapper">
                                        <div class="seat" data-seat-number="{{ $deskNumber * 2 - 1 }}">
                                            <p class="desk-number">Desk {{ $deskNumber * 2 - 1 }}</p>
                                            @if ($student1)
                                                <div class="student-container" draggable="true" data-student-id="{{ $student1->id }}">
                                                    <img src="{{ asset('storage/photos/' . $student1->photo) }}" alt="{{ $student1->name }}" class="student-img">
                                                    <p class="student-name">{{ $student1->name }}</p>
                                                </div>
                                            @else
                                                <div class="empty-seat" data-seat-number="{{ $deskNumber * 2 - 1 }}"></div>
                                            @endif
                                        </div>
                                        <div class="seat" data-seat-number="{{ $deskNumber * 2 }}">
                                            <p class="desk-number">Desk {{ $deskNumber * 2 }}</p>
                                            @if ($student2)
                                                <div class="student-container" draggable="true" data-student-id="{{ $student2->id }}">
                                                    <img src="{{ asset('storage/photos/' . $student2->photo) }}" alt="{{ $student2->name }}" class="student-img">
                                                    <p class="student-name">{{ $student2->name }}</p>
                                                </div>
                                            @else
                                                <div class="empty-seat" data-seat-number="{{ $deskNumber * 2 }}"></div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <img src="{{ asset('storage/photos/single.png') }}" alt="Desk {{ $deskNumber }}" class="desk-img">
                                    @php
                                        $student = $students->firstWhere('seat_number', $deskNumber);
                                    @endphp
                                    <div class="seat" data-seat-number="{{ $deskNumber }}">
                                        <p class="desk-number">Desk {{ $deskNumber }}</p>
                                        @if ($student)
                                            <div class="student-container" draggable="true" data-student-id="{{ $student->id }}">
                                                <img src="{{ asset('storage/photos/' . $student->photo) }}" alt="{{ $student->name }}" class="student-img">
                                                <p class="student-name">{{ $student->name }}</p>
                                            </div>
                                        @else
                                            <div class="empty-seat" data-seat-number="{{ $deskNumber }}"></div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </td>
                    @endfor
                </tr>
            @endfor
        </table>
    </div>
    <form id="update-seat-form" action="{{ route('update-seat') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="student_id" id="student_id">
        <input type="hidden" name="seat_number" id="seat_number">
    </form>
@endsection

@push('styles')
<style>
    .desk-table-container {
        width: 100%;
        max-width: 1000px;
        margin: auto;
        overflow-x: auto;
    }
    .desk-table {
        width: 100%;
        border-collapse: collapse;
    }
    .desk-table td {
        border: 1px solid #555555;
        padding: 10px;
        text-align: center;
        background-color: #999783;
        position: relative;
    }
    .desk-wrapper {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .desk-img {
        width: 100px;
        height: auto;
    }
    .student-wrapper {
        display: flex;
        justify-content: space-between;
        width: 100%;
    }
    .student-container, .empty-seat {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        cursor: pointer;
    }
    .student-container[draggable="true"] {
        cursor: grab;
    }
    .student-container[draggable="true"]:active {
        cursor: grabbing;
    }
    .student-img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        margin-bottom: 5px;
    }
    .student-name {
        margin: 5px 0;
        color: rgb(0, 0, 0);
    }
    .desk-number {
        margin-bottom: 5px;
    }
    .drag-over {
        border: 2px dashed #000;
    }
    .hidden {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const seats = document.querySelectorAll('.seat');

        seats.forEach(seat => {
            seat.addEventListener('dragover', (e) => {
                e.preventDefault();
                seat.classList.add('drag-over');
            });

            seat.addEventListener('dragleave', () => {
                seat.classList.remove('drag-over');
            });

            seat.addEventListener('drop', (e) => {
                e.preventDefault();
                seat.classList.remove('drag-over');

                const studentId = e.dataTransfer.getData('student-id');
                const studentElement = document.querySelector(`.student-container[data-student-id="${studentId}"]`);
                const newSeatNumber = seat.getAttribute('data-seat-number');

                if (studentElement && newSeatNumber) {
                    seat.appendChild(studentElement);

                    document.getElementById('student_id').value = studentId;
                    document.getElementById('seat_number').value = newSeatNumber;

                    const form = document.getElementById('update-seat-form');
                    form.submit();
                }
            });
        });

        const students = document.querySelectorAll('.student-container');

        students.forEach(student => {
            student.addEventListener('dragstart', (e) => {
                e.dataTransfer.setData('student-id', student.getAttribute('data-student-id'));
            });
        });

        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 3000);
        }
    });
</script>
@endpush


