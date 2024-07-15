<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;

class SeatController extends Controller
{
    public function availableSeats(Classroom $classroom)
    {
        $seats = $classroom->seats()->whereNull('student_id')->get();
        return response()->json($seats);
    }
}
