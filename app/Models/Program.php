<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;

class Program extends Model
{
    use HasFactory;

    protected $table = 'programs'; // nombre de la tabla en la base de datos
    protected $fillable = ['name', 'description', 'image_url']; // columnas que se pueden llenar

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
