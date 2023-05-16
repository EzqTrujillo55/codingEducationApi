<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Program;
use App\Models\School;

class Event extends Model
{
    use HasFactory;


    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'school_has_students', 'event_id', 'student_id');
    }

    // public function events()
    public function school()
    {
        return $this->hasOneThrough(School::class, SchoolHasEvent::class, 'event_id', 'id', 'id', 'school_id');
    }

    // chat gpt example
    // class Mechanic extends Model
    // {
    //     /**
    //      * Get the car's owner.
    //      */
    //     public function carOwner(): HasOneThrough
    //     {
    //         return $this->hasOneThrough(
    //             Owner::class,
    //             Car::class,
    //             'mechanic_id', // Foreign key on the cars table...
    //             'car_id', // Foreign key on the owners table...
    //             'id', // Local key on the mechanics table...
    //             'id' // Local key on the cars table...
    //         );
    //     }
    // }

}
