<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetallesMovimiento;
use App\Models\ControlAlmacen;
use Illuminate\Support\Facades\DB;
use Validator;
use Carbon\Carbon;

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

            if($request->nombre === "compra" || $request->nombre === "Compra"){

                $producto = DB::table('productos')
                ->where('id_producto', '=', $request->key_producto)
                ->first();

                $compra = $request->costo_unitario_actual * $request->unidades;
                $saldo_actual = $producto->saldo + $compra;
                $unidades = $producto->existencia + $request->unidades;

                DetallesMovimiento::create([
                    'costo_unitario_previo' => $request->costo_unitario_previo,
                    'costo_unitario_actual' => $request->costo_unitario_actual,
                    'nombre' => $request->nombre,
                    'unidades' => $request->unidades,
                    'key_producto' => $request->key_producto
                ]);

                ControlAlmacen::create([
                    'costo_unitario' => $request->costo_unitario_actual,
                    'nombre_producto' => $producto->nombre,
                    'key_producto' => $producto->id_producto,
                    'unidades' => $request->unidades, 
                    'status' => 1,
                ]);

                

                DB::table('productos') 
                ->where("id_producto", '=', $request->key_producto)
                ->update([
                    'existencia' => $unidades,
                    'saldo' => $saldo_actual,
                    'updated_at' => Carbon::now()->format('Y-m-d'),
                ]);
    
            }else if ($request->nombre === "Venta" || $request->nombre === "venta"){

                DetallesMovimiento::create([
                    'costo_unitario_previo' => $request->costo_unitario_previo,
                    'costo_unitario_actual' => $request->costo_unitario_actual,
                    'nombre' => $request->nombre,
                    'unidades' => $request->unidades,
                    'key_producto' => $request->key_producto
                ]);

                $ventas = DB::table('control_almacen')
                ->where('key_producto', '=', $request->key_producto)
                ->where('status', '=', 1)
                ->orderBy("created_at")
                ->first();

                if($request->unidades < $ventas->unidades){

                    $producto = DB::table('productos')
                    ->where('id_producto', '=', $request->key_producto)
                    ->first();

                    $resta_saldo = $producto->saldo - ($ventas->costo_unitario * $ventas->unidades);
                    $resta_unidades_control_almacen = $ventas->unidades - $request->unidades;
                    $resta_unidades_producto = $producto->existencia - $request->unidades;

                    $control_almacen =ControlAlmacen::where('key_producto', '=', $request->key_producto, )
                    ->where('status', 1)
                    ->first();
                    
                    $control_almacen->update([
                        'unidades' => $resta_unidades_control_almacen,
                        'updated_at' => Carbon::now()->format('Y-m-d'),
                    ]);

                    

                    DB::table('productos')
                    ->where('id_producto', '=', $request->key_producto)
                    ->update([
                        'saldo' => $resta_saldo,
                        'existencia' => $resta_unidades_producto,
                        'updated_at' => Carbon::now()->format('Y-m-d'),
                    ]);
                } else if( $request->unidades === $ventas->unidades ){

                    $producto = DB::table('productos')
                    ->where('id_producto', '=', $request->key_producto)
                    ->first();

                    $compra = $request->costo_unitario_actual * $request->unidades;
                    $saldo_actual = $producto->saldo + $compra;
                    $resta_unidades_producto = $producto->existencia - $request->unidades;
                    $resta_saldo = $producto->saldo - ($ventas->costo_unitario * $ventas->unidades);

                    $control_almacen =ControlAlmacen::where('key_producto', '=', $request->key_producto, )
                    ->where('status', 1)
                    ->first();
                    
                    $control_almacen->update([
                        'unidades' => 0,
                        'status' => 2,
                        'updated_at' => Carbon::now()->format('Y-m-d'),
                    ]);

                    DB::table('productos')
                    ->where('id_producto', '=', $request->key_producto)
                    ->update([
                        'saldo' => $resta_saldo,
                        'existencia' => $resta_unidades_producto,
                        'updated_at' => Carbon::now()->format('Y-m-d'),
                    ]);

                }
                
            }

            $status_code = 200;
            $response['Message'] = "Success";

            
        }

        return response()->json($response, $status_code);
    }

    public function getProductosVenta(Request $request){
        $status_code = 400;
        $response = [];

        $validator = Validator::make($request->all(), $rules = [
            'key_producto' => 'required|integer|exists:productos,id_producto',
        ]);

        if($validator->fails()){
            $response = $validator->errors();
       }else{

            $response = DB::table('control_almacen')
            ->where('key_producto', '=', $request->key_producto)
            ->orderBy("created_at")
            ->get();

            $status_code = 200;
        }

        return response()->json($response, $status_code);

    }

}
