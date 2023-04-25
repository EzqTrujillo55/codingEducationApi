<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Program;

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
}
