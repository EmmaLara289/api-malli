<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Productos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Crear tabla del producto
id_producto
costo u
existencia
saldo

crear tabla detalles_movimiento(conceptos)
id_concepto
nombre //compra o venta
key_producto
unidades //
        */
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('id_producto');
            $table->integer('costo_unitario');
            $table->integer('existencia');
            $table->string('codigo');
            $table->integer('saldo');
            $table->integer('status');
            $table->dateTime('created_at')->nullable('false');
            $table->dateTime('updated_at')->nullable('false');
            $table->dateTime('deleted_at')->nullable('true');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
