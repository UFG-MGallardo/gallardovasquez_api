<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zona;

class ZonaController extends Controller
{
    //

    public function obtenerZonas(){
        $Zona = new Zona();

        $satisfactorio = false;
        $estado = 0;
        $mensaje = "";
        $errores = [];
        $valores = [];

        $valores = $Zona::all();
        //Valores encontrados
        if(!empty($valores)){
            $satisfactorio = true;
            $estado = 200;
            $mensaje = "Valores encontrados";
            $errores = [
                "code" => 200,
                "msj" => ""
            ];
        }else{
            //Valores no encontrados
            $satisfactorio = false;
            $estado = 404;
            $mensaje = "No se han encontrado valores";
            $errores = [
                "code" => 404,
                "msj" => "Datos no encontrados"
            ];
        }

        //variable de salida
        $respuesta = [
            "success" => $satisfactorio,
            "status" => $estado,
            "msg" => $mensaje,
            "data" => $valores,
            "errors" => $errores,
            "total" => sizeof($valores)
        ];
        //retorna el mensaje al usuario
        return response()->json($respuesta,$estado);
    }

    public function obtenerZona(int $idzona = 0){

        $satisfactorio = false;
        $estado = 0;
        $mensaje = "";
        $errores = [];
        $valores = [];

        if($idzona > 0){
            $Zona = new Zona();
            $valores = $Zona->where('id_zona', $idzona)->get();

                //Valores encontrados
            if(!empty($valores)){
                $satisfactorio = true;
                $estado = 200;
                $mensaje = "Valores encontrados";
                $errores = [
                    "code" => 200,
                    "msj" => ""
                ];
            }else{
                //Valores no encontrados
                $satisfactorio = false;
                $estado = 404;
                $mensaje = "No se han encontrado valores";
                $errores = [
                    "code" => 404,
                    "msj" => "Datos no encontrados"
                ];
            }
        }else{
            $satisfactorio = false;
            $estado = 400;
            $mensaje = "No se ha enviado el parametro obligatorio";
            $errores = [
                "code" => 404,
                "msj" => "El identificador de la zona esta vacio"
            ];
        }

        
        //variable de salida
        $respuesta = [
            "success" => $satisfactorio,
            "status" => $estado,
            "msg" => $mensaje,
            "data" => $valores,
            "errors" => $errores,
            "total" => sizeof($valores)
        ];
        //retorna el mensaje al usuario
        return response()->json($respuesta,$estado);
    }
}
