@extends('layouts.app')

@section('header')
    <h2 class="text-2xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Home</h2>
@endsection

@section('content')
    <div class="flex flex-col items-center justify-center h-screen">
        <h1 class="text-4xl font-bold mb-8 text-gray-800 dark:text-gray-200">{{ config('app.name', 'Laravel') }}</h1>

        <div class="space-y-4">
            <a href="{{ route('classroom.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Manage Classrooms</a>
            <a href="{{ route('students.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Manage Students</a>
        </div>
    </div>

@endsection
