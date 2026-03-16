<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $table = 'ruta';
    protected $primaryKey = 'id_ruta';
    public $timestamps    = false;       

   protected $fillable = [
        'nombre',
        'descripcion',
        'dificultad',
        'distancia_km',
        'duracion_estimada',
        'recomendaciones',
        'fecha_inicio_operacion',
        'fecha_fin_operacion',
        'punto_inicio_lat',
        'punto_inicio_lng',
        'activo',
        'motivo_inactivo',
        'creado_por',
        'fecha_creacion',
    ];

    // Una ruta tiene muchos destinos a través de la tabla ruta_destino
    public function destinos()
    {
        return $this->belongsToMany(
            Destino::class,
            'ruta_destino',       // tabla pivote
            'id_ruta',            // llave foránea de esta clase
            'id_destino'          // llave foránea del otro modelo
        )->withPivot('orden')     // también queremos el campo "orden"
         ->orderBy('ruta_destino.orden'); // los traemos en orden
    }
}