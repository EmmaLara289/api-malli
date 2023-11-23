<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Validator;

class productosController extends Controller
{
    public function createProducto(Request $request){
        $status_code = 400;
        $response = [];

        $validator = Validator::make($request->all(), $rules = [
            'nombre' => 'required|string|min:5',
            'costo_unitario' => 'required|numeric|min:0.00|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        if($validator->fails()){
            $response = $validator->errors();
       }else{
            Producto::create([
                'nombre' => $request->nombre,
                'costo_unitario' => $request->costo_unitario,
                'status' => 0,
                'existencia' => 0,
                'saldo' => 0.00,
                'codigo' => "",
            ]);

            $status_code = 200;
            $response ['Message'] = "Success";
       }

       return response()->json($response, $status_code);

    }
}
