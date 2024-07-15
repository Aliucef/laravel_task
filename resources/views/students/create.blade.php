@extends('layouts.app')

@section('header')
    <h2 class="text-2xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Create Student</h2>
@endsection

@section('content')
    <form action="{{ route('students.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name:</label>
            <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
        </div>
        <div>
            <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Photo:</label>
            <input type="file" name="photo" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
        </div>
        <div>
            <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class:</label>
            <select name="class_id" id="class_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                <option value="">Select a class</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
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
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    document.getElementById('class_id').addEventListener('change', function() {
        var classId = this.value;
        var seatSelect = document.getElementById('seat_number');
        seatSelect.innerHTML = '<option value="">Select a seat</option>';

        var availableSeats = @json($availableSeats);

        if (availableSeats[classId] && availableSeats[classId].length > 0) {
            availableSeats[classId].forEach(function(seat) {
                seatSelect.innerHTML += `<option value="${seat}">S${seat}</option>`;
            });
        } else {
            seatSelect.innerHTML = '<option value="">No more Seats</option>';
        }
    });

    document.getElementById('class_id').dispatchEvent(new Event('change'));
</script>
@endpush
