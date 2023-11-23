<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DetallesMovimientos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    /*crear tabla detalles_movimiento(conceptos)
id_concepto
nombre //compra o venta
key_producto
unidades //
*/
    public function up()
    {
        Schema::create('detalles_movimientos', function (Blueprint $table) {
            $table->increments('id_detalle_moviento');
            $table->float('costo_unitario_previo');
            $table->float('costo_unitario_actual');
            $table->string('nombre');
            $table->integer('key_producto');
            $table->integer('unidades');
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
        Schema::dropIfExists('detalles_movimientos');
    }
}
