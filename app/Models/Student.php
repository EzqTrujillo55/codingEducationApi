<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\School;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'birthdate',
        'nationality',
        'passport',
        'valid_visa',
        'end_of_validity',
        'student_email',
        'residence_country',
        'city',
        'postal_code',
        'emergency_contact_full_name',
        'emergency_contact_relationship',
        'emergency_contact_email',
        'emergency_contact_phone_number',
        'parents_id'
    ];
    

    public function schools()
    {
        return $this->belongsToMany(School::class, 'school_has_students')
                    ->withPivot('entry_year', 'exit_year');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'school_has_students', 'student_id', 'event_id');
    }
}
