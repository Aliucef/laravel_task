@extends('layouts.app')

@section('header')
    <h2 class="text-2xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Edit Student</h2>
@endsection

@section('content')
    <form action="{{ route('students.update', $student->id) }}" method="POST" class="space-y-4" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name:</label>
            <input type="text" name="name" value="{{ $student->name }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
        </div>
        <div>
            <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Photo:</label>
            <input type="file" name="photo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
            @if($student->photo)
                <img src="{{ asset('storage/photos/' . $student->photo) }}" alt="Current Photo" class="mt-2 h-16 w-16 rounded-full">
            @endif
        </div>
        <div>
            <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class:</label>
            <select name="class_id" id="class_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                <option value="">Select a class</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ $classroom->id == $student->class_id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                @endforeach
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
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const classFilter = document.getElementById('class_id');
        const availableSeats = @json($availableSeats);
        const student = @json($student);

        classFilter.addEventListener('change', function() {
            const seatSelect = document.getElementById('seat_number');
            seatSelect.innerHTML = '<option value="">Select a seat</option>';

            if (availableSeats[this.value]) {
                if (availableSeats[this.value].length === 0) {
                    seatSelect.innerHTML += '<option value="">No more seats</option>';
                } else {
                    availableSeats[this.value].forEach(function(seat) {
                        const selected = seat == student.seat_number ? 'selected' : '';
                        seatSelect.innerHTML += `<option value="${seat}" ${selected}>S${seat}</option>`;
                    });
                }
            }
        });

     
        classFilter.dispatchEvent(new Event('change'));
    });
</script>
@endpush
