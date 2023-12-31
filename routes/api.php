<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\detallesMovientoController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/createProducto', [productosController::class, 'createProducto']);

Route::post('/createDetalleMovimiento', [detallesMovientoController::class, 'createDetalleMovimiento']);

Route::get('/getProductosVenta', [detallesMovientoController::class, 'getProductosVenta']);
