<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'photo', 'class_id' ,'seat_number','student_id'];

    public function class()
    {
        return $this->belongsTo(Classroom::class);
    }
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function seat()
    {
        return $this->hasOne(Seat::class);
    }
}

