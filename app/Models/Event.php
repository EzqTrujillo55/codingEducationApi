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
}
