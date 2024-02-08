<?php

namespace App\Http\Controllers;

use App\Models\RentaLibro;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RentaLibrosController extends Controller
{

    public function verLibrosRentados()
    {
        return RentaLibro::paginate(20);
    }

    public function realizarRentaLibro(Request $request)
    {
        $usuario_id = $request->usuario_id;
        $libro_id = $request->libro_id;

        try {
            $nueva_renta = RentaLibro::create(compact('usuario_id', 'libro_id'));
        } catch (Exception $e) {
            return response(['mensaje' => $e->getMessage()], 401);
        }
        return response([
            'mensaje' => 'Se ha rentado el libro correctamente',
            'renta' => $nueva_renta
        ], 200);
    }

    public function devolucionLibro(RentaLibro $renta)
    {
        $renta->delete();
        return response(['mensaje' => 'El libro rentado ha sido devuelto'], 200);
    }

}
