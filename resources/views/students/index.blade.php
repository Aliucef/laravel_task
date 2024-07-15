@extends('layouts.app')

@section('header')
    <h2 class="text-2xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Students</h2>
@endsection

@section('content')
    <div class="space-y-4">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center space-x-2">
                <div class="flex items-center space-x-2">
                    {{-- hyda l filtering drop down menu --}}
                    <form id="filter_form" action="{{ url('/students/filter') }}" method="GET">
                        <label for="class_filter" class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter by Class:</label>
                        <select id="class_filter" name="class_id" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-300">
                            <option value="">All</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('students.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add New Student
                </a>
                <a href="{{ route('students.assignClassForm') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Assign Class
                </a>
            </div>
        </div>

        <div id="student_list_container">
            @include('students.partials.student_list', ['students' => $students])
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const classFilter = document.getElementById('class_filter');
        classFilter.addEventListener('change', function() {
            const filterForm = document.getElementById('filter_form');
            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData).toString();
            const url = filterForm.action + '?' + params;

            fetch(url)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('student_list_container').innerHTML = data;
                })
                .catch(error => {
                    console.error('Error fetching students:', error);
                });
        });
    });
</script>
@endpush
