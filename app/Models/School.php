<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Representative;
use App\Models\Student;
use App\Models\Event;
use App\Models\SchoolHasEvent;

class School extends Model
{
    use HasFactory;

    public function representative()
    {
        return $this->hasOne(Representative::class, 'id', 'representative_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'school_has_students');                    
    }

    public function events()
    {
        return $this->hasManyThrough(Event::class, SchoolHasEvent::class, 'school_id', 'id', 'id', 'event_id');
    }

}
