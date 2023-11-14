<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolHasPrograms extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'program_id',
        'start_date',
        'end_date',
        'payment_limit',
        'price',
        'initial_fee'
    ];

    public function school()
    {
        return $this->hasOneThrough(School::class, SchoolHasPrograms::class, 'event_id', 'id', 'id', 'school_id');
    }
}
