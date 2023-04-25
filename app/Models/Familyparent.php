<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Familyparent extends Model
{
    use HasFactory;

    protected $fillable = [
        'mothers_name',
        'mothers_phone',
        'mothers_email',
        'fathers_name',
        'fathers_phone',
        'fathers_email',
        'user_id'
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'id', 'user_id');
    }
    
}
