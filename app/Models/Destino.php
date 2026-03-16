<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destino extends Model
{
    protected $table = 'destino';
    protected $primaryKey = 'id_destino';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'lat',
        'lng',
        'telefono',
        'recomendaciones',
        'activo',
        'creado_por'
    ];
}
