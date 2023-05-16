<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Models\User;
use App\Models\Student;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice',
        'amount',
        'event_id',
        'student_id'
    ];
    
    public function event()
    {
        return $this->hasOne(Event::class, 'id', 'event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function student()
    {
        return $this->hasOne(Student::class, 'id', 'student_id');
    }
   
}