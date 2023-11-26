<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlAlmacen extends Model
{
    use HasFactory;

    protected $table = 'control_almacen';
    protected $primaryKey = 'id_control_almacen';
    public $incrementing = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /*
     $table->increments('id_control_almacen');
            $table->integer('key_producto');
            $table->string('nombre_producto');
            $table->float('costo_unitario');
            $table->integer('unidades');
            $table->integer('status');
            $table->dateTime('created_at')->nullable('false');
            $table->dateTime('updated_at')->nullable('false');
            $table->dateTime('deleted_at')->nullable('true');
    */
    protected $fillable = [
        'costo_unitario',
        'nombre_producto',
        'key_producto',
        'unidades', 
        'status',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
    ];
    
}
