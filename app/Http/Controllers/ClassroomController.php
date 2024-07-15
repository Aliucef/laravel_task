<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Seat;



class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::all();
        return view('classroom.index', compact('classrooms'));
    }

    public function create()
    {
        return view('classroom.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'seat_type' => 'required',
            'dimensions' => 'required|regex:/^\d+x\d+$/',
        ]);

        // Calculate the number of seats based on dimensions and seat type
        list($length, $width) = explode('x', $request->dimensions);
        $number_of_seats = $length * $width;
        if ($request->seat_type == 'double') {
            $number_of_seats *= 2;
        }

        Classroom::create([
            'name' => $request->name,
            'seat_type' => $request->seat_type,
            'dimensions' => $request->dimensions,
            'number_of_seats' => $number_of_seats,
        ]);

        return redirect()->route('classroom.index');
    }



    public function assignSeat(Request $request)
    {
        $student = Student::find($request->student_id);
        $seat = Seat::find($request->seat_id);

        if ($seat && $student) {
            $classroom = $seat->classroom;

            $currentSeatsOccupied = $classroom->seats()->whereNotNull('student_id')->count();
            if ($currentSeatsOccupied >= $classroom->number_of_seats) {
                return response()->json(['error' => 'Classroom is full'], 400);
            }

            $seat->student_id = $student->id;
            $seat->save();

            return response()->json([
                'name' => $student->name,
                'photo' => asset('storage/' . $student->photo)
            ]);
        }

        return response()->json(['error' => 'Invalid student or seat'], 400);
    }

    public function getAvailableSeats($id)
{
    $classroom = Classroom::find($id);
    if (!$classroom) {
        return response()->json(['error' => 'Classroom not found'], 404);
    }

    $totalSeats = $this->calculateSeats($classroom->dimensions, $classroom->seat_type);
    $occupiedSeats = Student::where('class_id', $classroom->id)->pluck('seat_number')->toArray();
    $availableSeats = array_diff(range(1, $totalSeats), $occupiedSeats);

    return response()->json(['availableSeats' => $availableSeats]);
}
public function showDesks($id)
{
    $classroom = Classroom::findOrFail($id);

    // Calculate rows and columns from dimensions
    list($cols, $rows) = explode('x', $classroom->dimensions);

    // Get students assigned to this classroom
    $students = Student::where('class_id', $id)->get();

    return view('classroom.desks', compact('classroom', 'rows', 'cols', 'students'));
}
public function edit($id)
{
    $classroom = Classroom::findOrFail($id);
    return view('classroom.edit', compact('classroom'));
}

//update classroom
public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'seat_type' => 'required',
        'dimensions' => 'required|regex:/^\d+x\d+$/',
    ]);

    $classroom = Classroom::findOrFail($id);

    // Calculate the number of seats based on dimensions and seat type
    list($length, $width) = explode('x', $request->dimensions);
    $newNumberOfSeats = $length * $width;
    if ($request->seat_type == 'double') {
        $newNumberOfSeats *= 2;
    }

    // Find the highest seat number occupied by any student in this classroom
    $highestSeatNumber = Student::where('class_id', $id)->max('seat_number');

    if ($highestSeatNumber > $newNumberOfSeats) {
        return back()->withErrors(['number_of_seats' => 'Cannot decrease the number of seats below the highest occupied seat number (' . $highestSeatNumber . ').']);
    }

    $classroom->update([
        'name' => $request->name,
        'seat_type' => $request->seat_type,
        'dimensions' => $request->dimensions,
        'number_of_seats' => $newNumberOfSeats,
    ]);

    return redirect()->route('classroom.index')->with('success', 'Classroom updated successfully');
}

// delete classroom
public function destroy($id)
{
    $classroom = Classroom::findOrFail($id);
    $classroom->delete();
    return redirect()->route('classroom.index')->with('success', 'Classroom deleted successfully');
}


}

