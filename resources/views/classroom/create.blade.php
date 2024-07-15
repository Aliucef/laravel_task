@extends('layouts.app')

@section('header')
    <h2 class="text-2xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Create Classroom</h2>
@endsection

@section('content')
    <form action="{{ route('classrooms.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name:</label>
            <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
        </div>
        <div>
            <label for="seat_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Seat Type:</label>
            <select name="seat_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                <option value="single">Single</option>
                <option value="double">Double</option>
            </select>
        </div>
        <div>
            <label for="dimensions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dimensions (e.g., 4x4):</label>
            <input type="text" name="dimensions" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
        </div>
        <div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button>
        </div>
    </form>
@endsection
