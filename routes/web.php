<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SeatController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

Route::get('/classroom/index', [ClassroomController::class, 'index'])->name('classroom.index');
Route::get('/classroom/create', [ClassroomController::class, 'create'])->name('classrooms.create');
Route::post('/classroom/create', [ClassroomController::class, 'store'])->name('classrooms.store');
Route::post('/assign-seat', [ClassroomController::class, 'assignSeat'])->name('assignSeat');

Route::get('/students', [StudentController::class, 'index'])->name('students.index');
Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
Route::post('/students', [StudentController::class, 'store'])->name('students.store');
Route::get('/students/filter', [StudentController::class, 'filterByClass']);
Route::get('/students/get-available-seats/{classId}', [StudentController::class, 'getAvailableSeats']);
Route::get('/classroom/{id}/available-seats', [ClassroomController::class, 'getAvailableSeats']);
Route::get('/students/assign', [StudentController::class, 'assignClassForm'])->name('students.assignClassForm');
Route::post('/students/assign', [StudentController::class, 'assignClass'])->name('students.assignClass');
Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
Route::get('/classrooms/{id}/desks', [ClassroomController::class, 'showDesks'])->name('classrooms.showDesks');
Route::post('/update-seat', [StudentController::class, 'updateSeat'])->name('update-seat');

Route::get('/classrooms/{id}/edit', [ClassroomController::class, 'edit'])->name('classrooms.edit');
Route::put('/classrooms/{id}', [ClassroomController::class, 'update'])->name('classrooms.update');
Route::delete('/classrooms/{id}', [ClassroomController::class, 'destroy'])->name('classrooms.destroy');
Route::resource('classroom', ClassroomController::class);

});

require __DIR__.'/auth.php';
