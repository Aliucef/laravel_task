@extends('layouts.app')

@section('content')
    <h2>Classroom Layout</h2>
    <div id="classroom-grid" style="display: grid; grid-template-columns: repeat({{ $columns }}, 1fr); gap: 10px;">
        @foreach($grid as $seat)
            <div class="seat" data-seat-id="{{ $seat->id }}">
                @if($seat->student)
                    <img src="{{ asset('storage/' . $seat->student->photo) }}" alt="{{ $seat->student->name }}">
                @endif
            </div>
        @endforeach
    </div>

    <h2>Unassigned Students</h2>
    <div id="unassigned-students">
        @foreach($unassignedStudents as $student)
            <div class="student" data-student-id="{{ $student->id }}">
                <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}">
                <p>{{ $student->name }}</p>
            </div>
        @endforeach
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function() {
            $(".student").draggable({
                revert: "invalid"
            });

            $(".seat").droppable({
                accept: ".student",
                drop: function(event, ui) {
                    var studentId = ui.draggable.data("student-id");
                    var seatId = $(this).data("seat-id");

                    // Send AJAX request to assign student to seat
                    $.post("{{ route('assignSeat') }}", {
                        _token: "{{ csrf_token() }}",
                        student_id: studentId,
                        seat_id: seatId
                    }).done(function(data) {
                        // Update seat with student photo
                        $(event.target).html('<img src="' + data.photo + '" alt="' + data.name + '">');
                        ui.draggable.remove();
                    });
                }
            });
        });
    </script>
@endsection
