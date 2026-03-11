<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $table = 'ruta';
    protected $primaryKey = 'id_ruta';

    protected $fillable = [
        'nombre',
        'descripcion',
        'distancia_km',
        'duracion_estimada',
        'dificultad',
        'recomendaciones',
        'punto_inicio_lat',
        'punto_inicio_lng',
        'punto_fin_lat',
        'punto_fin_lng',
        'ruta_polyline',
        'google_directions_url',
        'activo',
        'creado_por',
    ];
}