<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AutenticacionController extends Controller
{
    /**
     * Registramos al usuario y le devolvemos un JWT
     */
    public function registro(Request $request)
    {
        $validador =  Validator::make($request->all(), [
            'nombre' => 'required|max:255',
            'email' => 'required|email',
            'password' => 'required',
            'confirmar_password' => 'required',
        ]);

        if($validador->fails())
            return response($validador->errors()->toJson(), Response::HTTP_BAD_REQUEST);

        $datos = $validador->validated();
        if($datos['password'] != $datos['confirmar_password'])
        {
            return response()->json([
                'mensaje' => 'Las contraseñas no coinciden'
            ], Response::HTTP_BAD_REQUEST);
        }
        
        $datos['password'] = Hash::make($datos['password']);
        $nuevo_usuario = User::create($datos);

        $respuesta = [
            'token' => JWTAuth::fromUser($nuevo_usuario),
            'usuario' => $nuevo_usuario
        ];

        return response()->json($respuesta, 201);
    }

    /**
     * Logueo para el usuario
     */
    public function login(Request $request)
    {
        $validador =  Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validador->fails())
            return response()->json($validador->errors()->toJson(), 400);

        $credenciales = $request->only('email', 'password');
        if(! $token = JWTAuth::attempt($credenciales)) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        return response()->json($token, 200);
    }

    /**
     * Obtenemos informacion del usuario mediante el JWT
     */
    public function getUser(Request $request)
    {
        $user = JWTAuth::authenticate($request->token);
        return response()->json(['usuario' => $user]);
    }

    /**
     * Cerramos la sesion del usuario invalidando su JWT
     */
    public function logout(Request $request)
    {
        JWTAuth::invalidate(JWTAuth::parseToken($request->token));
        return response()->json(['mensaje' => 'Cierre de sesión exitoso']);
    }

    /**
     * Refrescamos el JWT del usuario
     */
    public function refresh()
    {
        $token = JWTAuth::getToken();
        return response([
            'nuevo_token' => JWTAuth::refresh($token)
        ], 200);
    }

}
