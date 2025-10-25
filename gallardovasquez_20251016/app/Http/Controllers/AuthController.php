<?php

namespace App\Http\Controllers;

use App\Models\User; // El modelo User de Laravel
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Login
use Illuminate\Support\Facades\Hash; // Encriptación
use Illuminate\Support\Facades\Validator; // Validación

class AuthController extends Controller
{
    // Creación de un nuevo usuario.

    public function register(Request $request)
    {
        $satisfactorio = false;
        $estado = 0;
        $mensaje = "";
        $errores = [];
        $token = "";


        // Validaciones
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users', // 'unique:users' para evitar emails duplicados
            'password' => 'required|string|min:8|max:20',
        ]);

        if (!$validator->fails()) 
        {
            // Crear el usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Encriptar la contraseña
            ]);

            // Crear un token para el nuevo usuario
            $token = $user->createToken('auth_token')->plainTextToken;

            $satisfactorio = true;
            $estado = 201;
            $mensaje = "Usuario registrado exitosamente";
            $errores = [
                "code" => 201,
                "msj" => ""
            ];

        } 
        else 
        {
            $satisfactorio = false;
            $estado = 400;
            $mensaje = "Datos inválidos";
            $errores = [
                "code" => 400,
                "msj" => $validator->errors()
            ];
        }

        //variable de salida
        $respuesta = [
            "success" => $satisfactorio,
            "status" => $estado,
            "msg" => $mensaje,
            "data" => [
                'access_token' => $token,
                'token_type' => 'Bearer',
            ],
            "errors" => $errores
        ];
        //retorna el mensaje al usuario
        return response()->json($respuesta,$estado);
    }




    //Iniciar sesión en el backend.


    public function login(Request $request)
    {
        $satisfactorio = false;
        $estado = 0;
        $mensaje = "";
        $errores = [];
        $token = "";


        // Validación (Verificar que el usuario exista)
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);


        if (!$validator->fails()) 
        {
            if (Auth::attempt($request->only('email', 'password'))) 
            {
                // Si las credenciales son correctas, busca al usuario
                $user = User::where('email', $request['email'])->firstOrFail();

                // Crea el token
                $token = $user->createToken('auth_token')->plainTextToken;

                $satisfactorio = true;
                $estado = 200;
                $mensaje = "Inicio de sesión exitoso";
                $errores = [
                    "code" => 200,
                    "msj" => ""
                ];
            }
            else
            {
                $satisfactorio = false;
                $estado = 401;
                $mensaje = "No se reconocen las credenciales";
                $errores = [
                    "code" => 401,
                    "msj" => "No se reconocen las credenciales"
                ];
            }
        } 
        else 
        {
            $satisfactorio = false;
            $estado = 400;
            $mensaje = "Email o contraseña no proporcionados";
            $errores = [
                "code" => 400,
                "msj" => "Email o contraseña no proporcionados"
            ];
        }

        
        //variable de salida
        $respuesta = [
            "success" => $satisfactorio,
            "status" => $estado,
            "msg" => $mensaje,
            "data" => [
                'access_token' => $token,
                'token_type' => 'Bearer',
            ],
            "errors" => $errores
        ];
        //retorna el mensaje al usuario
        return response()->json($respuesta,$estado);
    }


    // Obtener detalle del usuario que ha iniciado sesión
    
    public function me(Request $request)
    {
        // Gracias al middleware 'auth:sanctum', Laravel ya sabe quién es el usuario.
        // Podemos acceder a él con $request->user()
        
        $user = $request->user();

        // Éxito (según imagen)
        return response()->json([
            'success' => true,
            'errors' => null,
            'data' => $user, // Devuelve el objeto del usuario autenticado
            'msg' => 'Detalle de usuario obtenido',
            'count' => 1
        ], 200);
    }
}