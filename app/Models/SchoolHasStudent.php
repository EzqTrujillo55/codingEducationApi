<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolHasStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'student_id',
        'event_id'
    ];
    
}
