<?php

use App\Http\Controllers\AutenticacionController;
use App\Http\Controllers\LibrosController;
use App\Http\Controllers\RentaLibrosController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', [AutenticacionController::class, 'login']);
Route::post('registro', [AutenticacionController::class, 'registro']);

Route::group(['middleware' => ['jwt.verify']], function(){
    Route::get('refresh', [AutenticacionController::class, 'refresh']);
    Route::get('usuario', [AutenticacionController::class, 'getUser']);
    Route::get('logout', [AutenticacionController::class, 'logout']);

    Route::apiResource('libros', LibrosController::class);
    Route::get('disponibilidad/{localidad}/libro/{nombre}', [LibrosController::class, 'obtenerLibrosLocalidadNombre']);

    Route::get('renta_libros', [RentaLibrosController::class, 'verLibrosRentados']);
    Route::post('renta_libros', [RentaLibrosController::class, 'realizarRentaLibro']);
    Route::delete('renta_libros/{renta}', [RentaLibrosController::class, 'devolucionLibro']);
});
