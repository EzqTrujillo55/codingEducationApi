<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolHasEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'event_id'
    ];

    public function event()
    {
        return $this->hasOne(Event::class, 'id', 'event_id');
    }
}
