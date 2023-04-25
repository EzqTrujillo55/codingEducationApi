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
        return $this->belongsToMany(Student::class, 'school_has_students')
                    ->withPivot('entry_year', 'exit_year');
    }

}
