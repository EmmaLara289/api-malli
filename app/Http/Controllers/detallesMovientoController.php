<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetallesMovimiento;
use Illuminate\Support\Facades\DB;
use Validator;

class detallesMovientoController extends Controller
{
    public function createDetalleMovimiento(Request $request){
        $status_code = 400;
        $response = [];

        $validator = Validator::make($request->all(), $rules = [
            'costo_unitario_previo' => 'required|numeric|min:0.00|regex:/^\d+(\.\d{1,2})?$/',
            'costo_unitario_actual' => 'required|numeric|min:0.00|regex:/^\d+(\.\d{1,2})?$/',
            'nombre' => 'required|string|min:5',
            'key_producto' => 'required|integer|exists:productos,id_producto',
            'unidades' => 'required|integer|min:1', 
        ]);

        if($validator->fails()){
            $response = $validator->errors();
       }else{
            DetallesMovimiento::create([
                'costo_unitario_previo' => $request->costo_unitario_previo,
                'costo_unitario_actual' => $request->costo_unitario_actual,
                'nombre' => $request->nombre,
                'unidades' => $request->unidades,
                'key_producto' => $request->key_producto
            ]);

            $status_code = 200;
            $response['Message'] = "Success";

            
        }

        return response()->json($response, $status_code);
    }
}
