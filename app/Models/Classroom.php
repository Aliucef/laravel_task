<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'number_of_seats',
        'seat_type',
        'dimensions',
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function studentCount()
    {
        return $this->students()->count();
    }


    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function calculateNumberOfSeats()
    {
        $dimensions = explode('x', $this->dimensions);
        $area = $dimensions[0] * $dimensions[1];

        return $this->seat_type === 'double' ? $area * 2 : $area;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($classroom) {
            $classroom->number_of_seats = $classroom->calculateNumberOfSeats();
        });

        static::updating(function ($classroom) {
            $classroom->number_of_seats = $classroom->calculateNumberOfSeats();
        });
    }
}

