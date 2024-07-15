@extends('layouts.app')

@section('header')
    <h2 class="text-2xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Assign Class to Student</h2>
@endsection

@section('content')
    <form action="{{ route('students.assignClass') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Student ID:</label>
            <select name="student_id" id="student_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                <option value="">Select a student ID</option>
                @php
                    $distinctStudentIds = [];
                @endphp
                @foreach($students as $student)
                    @if(!in_array($student->student_id, $distinctStudentIds))
                        <option value="{{ $student->student_id }}">{{ $student->student_id }}</option>
                        @php
                            $distinctStudentIds[] = $student->student_id;
                        @endphp
                    @endif
                @endforeach
            </select>
            <div id="student_name_display" class="mt-2 text-sm text-gray-500 dark:text-gray-400"></div>
        </div>
        <div>
            <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class:</label>
            <select name="class_id" id="class_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                <option value="">Select a class</option>
                {{--  classes will be dynamically displayed here --}}
            </select>
        </div>
        <div>
            <label for="seat_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Seat Number:</label>
            <select name="seat_number" id="seat_number" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                <option value="">Select a seat</option>
                {{--  Seats will be dynamically added here --}}
            </select>
        </div>
        <div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Assign</button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const classFilter = document.getElementById('class_id');
        const availableSeats = @json($availableSeats);
        const students = @json($students);
        const classrooms = @json($classrooms);

        const studentFilter = document.getElementById('student_id');
        studentFilter.addEventListener('change', function() {
            const selectedStudentId = this.value;
            const studentNameDisplay = document.getElementById('student_name_display');
            const selectedStudent = students.find(student => student.student_id == selectedStudentId);

            if (selectedStudent) {
                studentNameDisplay.textContent = `Selected Student: ${selectedStudent.name}`;
            } else {
                studentNameDisplay.textContent = '';
            }

   // Filter classes based on the selected student
            const classSelect = document.getElementById('class_id');
            classSelect.innerHTML = '<option value="">Select a class</option>';

            const assignedClassIds = students
                .filter(student => student.student_id == selectedStudentId)
                .map(student => student.class_id);

            classrooms.forEach(classroom => {
                if (!assignedClassIds.includes(classroom.id)) {
                    classSelect.innerHTML += `<option value="${classroom.id}">${classroom.name}</option>`;
                }
            });

 // Trigger change event on class select to update seats
            classSelect.dispatchEvent(new Event('change'));
        });

        classFilter.addEventListener('change', function() {
            const seatSelect = document.getElementById('seat_number');
            seatSelect.innerHTML = '<option value="">Select a seat</option>';

            if (availableSeats[this.value]) {
                if (availableSeats[this.value].length === 0) {
                    seatSelect.innerHTML += '<option value="">No more seats</option>';
                } else {
                    availableSeats[this.value].forEach(function(seat) {
                        seatSelect.innerHTML += `<option value="${seat}">S${seat}</option>`;
                    });
                }
            }
        });
    });
</script>
@endpush
