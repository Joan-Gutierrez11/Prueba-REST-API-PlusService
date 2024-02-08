<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentaLibro extends Model
{
    use HasFactory;

    protected $table = 'renta_libros';

    protected $fillable = [
        'usuario_id',
        'libro_id',
    ];

    protected static function booted(): void
    {
        // En el momento de crear la orden de renta se verifica si hay libros disponibles
        static::creating(function (RentaLibro $rentaLibro) {
            $cantidad_libros = Libro::find($rentaLibro->libro_id)->cantidad;
            if($cantidad_libros == 0)
                throw new Exception('No hay libros disponibles');

            $libro = Libro::find($rentaLibro->libro_id)->first();
            $libro->update(['cantidad' => $cantidad_libros-1]);
        });

        // Al momento de eliminar la orden de renta se agrega disponibilidad al libro.
        static::deleting(function (RentaLibro $rentaLibro) {
            $libro = Libro::find($rentaLibro->libro_id)->first();
            $libro->update(['cantidad' => $libro->cantidad+1]);
        });
    }
}
