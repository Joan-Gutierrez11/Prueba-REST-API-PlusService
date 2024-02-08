<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LibrosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Libro::query();
        if($request->query('orden_fecha') && in_array($request->query('orden_fecha'), ['asc', 'desc']))
            $query = $query->orderBy('fecha_lanzamiento', $request->query('orden_fecha'));

        if($request->query('orden_alfab') && in_array($request->query('orden_alfab'), ['asc', 'desc']))
            $query = $query->orderBy('libro', $request->query('orden_alfab'));

        if($request->query('categoria'))
            $query = $query->where('categoria', 'LIKE', '%'.$request->query('categoria').'%');

        $libros = $query->paginate(20);
        return response()->json($libros, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validador =  Validator::make($request->all(), [
            'libro' => 'required|max:255',
            'cantidad' => 'required',
            'localizacion' => 'required',
            'categoria' => 'required',
        ]);

        if($validador->fails())
            return;

        $nuevo_libro = Libro::create($validador->validated());
        return response()->json($nuevo_libro, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Libros  $libros
     * @return \Illuminate\Http\Response
     */
    public function show(Libro $libro)
    {
        return response()->json($libro, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Libro  $libro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Libro $libro)
    {
        $libro->update($request->all());
        return response()->json($libro, 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Libro  $libro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Libro $libro)
    {
        $libro->delete();
        return response()->json($libro, 202);
    }

    /**
     * 
     */
    public function obtenerLibrosLocalidadNombre(Request $request, string $localidad, string $nombre)
    {
        $query = Libro::where('localidad', '=', ucfirst($localidad))
            ->where('categoria', 'LIKE', '%'.$nombre.'%');
        $libros = $query->get();

        if($libros->isEmpty())
            return response()->json([ 
                "mensaje" => "No se encontraron los libros en esa localidad o que tengan ese nombre" ], 404);

        return response()->json([ "localidad" => $libros ], 200);
    }

}
