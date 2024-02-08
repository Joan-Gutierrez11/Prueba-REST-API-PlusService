<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

    protected $fillable = [
        'libro',
        'cantidad',
        'localizacion',
        'categoria'
    ];

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'renta_libros', 'libro_id', 'usuario_id');
    }
}
