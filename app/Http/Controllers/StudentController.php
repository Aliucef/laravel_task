<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Classroom;

class StudentController extends Controller
{
    public function index()
    {
        $classes = Classroom::all();
        $students = Student::all(); // Fetch all students initially

        return view('students.index', compact('students', 'classes'));
    }


    public function filterByClass(Request $request)
    {
        $class_id = $request->input('class_id');

        if ($class_id) {
            $students = Student::where('class_id', $class_id)->get();
        } else {
            $students = Student::all();
        }

        return view('students.partials.student_list', compact('students'));
    }
    public function assignClassForm()
    {
        $students = Student::all();
        $classrooms = Classroom::all();
        $availableSeats = [];

        foreach ($classrooms as $classroom) {
            $totalSeats = Classroom::where('id', $classroom->id)->value('number_of_seats');
            $occupiedSeats = Student::where('class_id', $classroom->id)->pluck('seat_number')->toArray();
            $occupiedSeats = array_map('intval', $occupiedSeats);

            $availableSeats[$classroom->id] = $this->getAvailableSeats($totalSeats, $occupiedSeats);
        }

        return view('students.assign', compact('students', 'classrooms', 'availableSeats'));
    }




    public function assignClass(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'class_id' => 'required|exists:classrooms,id',
            'seat_number' => 'required|integer',
        ]);

        $classroom = Classroom::find($request->class_id);
        $totalSeats = $this->calculateSeats($classroom->dimensions, $classroom->seat_type);
        $occupiedSeats = Student::where('class_id', $request->class_id)->pluck('seat_number')->toArray();

        if (in_array($request->seat_number, $occupiedSeats)) {
            return back()->withErrors(['seat_number' => 'This seat is already occupied.']);
        }

        if ($request->seat_number > $totalSeats) {
            return back()->withErrors(['seat_number' => 'Invalid seat number.']);
        }

        $existingStudent = Student::where('student_id', $request->student_id)->first();

        $student = new Student();
        $student->name = $existingStudent->name;
        $student->photo = $existingStudent->photo;
        $student->class_id = $request->class_id;
        $student->seat_number = $request->seat_number;
        $student->student_id = $existingStudent->student_id;
        $student->save();

        return redirect()->route('students.index');
    }

    public function create()
    {
        $classrooms = Classroom::all();
        $availableSeats = [];

        foreach ($classrooms as $classroom) {
            $totalSeats = Classroom::where('id', $classroom->id)->value('number_of_seats');
            $occupiedSeats = Student::where('class_id', $classroom->id)->pluck('seat_number')->toArray();
            $occupiedSeats = array_map('intval', $occupiedSeats);

            $availableSeats[$classroom->id] = $this->getAvailableSeats($totalSeats, $occupiedSeats);
        }

        return view('students.create', compact('classrooms', 'availableSeats'));
    }

    private function getAvailableSeats($totalSeats, $occupiedSeats)
    {
        $availableSeats = [];
        for ($i = 1; $i <= $totalSeats; $i++) {
            if (!in_array($i, $occupiedSeats)) {
                $availableSeats[] = $i;
            }
        }
        return $availableSeats;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:20048',
            'class_id' => 'required|exists:classrooms,id',
            'seat_number' => 'required|integer',
        ]);

        $classroom = Classroom::find($request->class_id);
        $totalSeats = $this->calculateSeats($classroom->dimensions, $classroom->seat_type);
        $occupiedSeats = Student::where('class_id', $request->class_id)->pluck('seat_number')->toArray();

        if (in_array($request->seat_number, $occupiedSeats)) {
            return back()->withErrors(['seat_number' => 'This seat is already occupied.']);
        }

        if ($request->seat_number > $totalSeats) {
            return back()->withErrors(['seat_number' => 'Invalid seat number.']);
        }

        $photoPath = $request->file('photo')->store('public/photos');
        $photoUrl = basename($photoPath);

        $studentId = Student::max('student_id') + 1;

        $student = new Student();
        $student->name = $request->name;
        $student->photo = $photoUrl;
        $student->class_id = $request->class_id;
        $student->seat_number = $request->seat_number;
        $student->student_id = $studentId;
        $student->save();

        return redirect()->route('students.index');
    }

    private function calculateSeats($dimensions, $seatType)
    {
        list($width, $length) = explode('x', $dimensions);
        $totalSeats = (int)$width * (int)$length;
        return $seatType === 'double' ? $totalSeats * 2 : $totalSeats;
    }


    // Edit student view
public function edit($id)
{
    $student = Student::find($id);
    $classrooms = Classroom::all();
    $availableSeats = [];

    foreach ($classrooms as $classroom) {
        $totalSeats = Classroom::where('id', $classroom->id)->value('number_of_seats');
        $occupiedSeats = Student::where('class_id', $classroom->id)->pluck('seat_number')->toArray();
        $occupiedSeats = array_map('intval', $occupiedSeats);

        $availableSeats[$classroom->id] = $this->getAvailableSeats($totalSeats, $occupiedSeats);
    }

    return view('students.edit', compact('student', 'classrooms', 'availableSeats'));
}

// Update student
public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'class_id' => 'required|exists:classrooms,id',
        'seat_number' => 'required|integer',
    ]);

    $student = Student::find($id);

    if ($request->hasFile('photo')) {
        $photoPath = $request->file('photo')->store('public/photos');
        $photoUrl = basename($photoPath);
        $student->photo = $photoUrl;
    }

    $classroom = Classroom::find($request->class_id);
    $totalSeats = $this->calculateSeats($classroom->dimensions, $classroom->seat_type);
    $occupiedSeats = Student::where('class_id', $request->class_id)->pluck('seat_number')->toArray();

    if (in_array($request->seat_number, $occupiedSeats)) {
        return back()->withErrors(['seat_number' => 'This seat is already occupied.']);
    }

    if ($request->seat_number > $totalSeats) {
        return back()->withErrors(['seat_number' => 'Invalid seat number.']);
    }

    $student->name = $request->name;
    $student->class_id = $request->class_id;
    $student->seat_number = $request->seat_number;
    $student->save();

    return redirect()->route('students.index');
}

// Delete student
public function destroy($id)
{
    $student = Student::find($id);
    $student->delete();

    return redirect()->route('students.index');
}

  public function updateSeat(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'seat_number' => 'required|integer'
        ]);

        $student = Student::findOrFail($request->student_id);
        $student->seat_number = $request->seat_number;
        $student->save();

        return redirect()->back()->with('success', 'Seat updated successfully.');
    }
}

