<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reserva_hotel';
    protected $primaryKey = 'id_reserva';
    public $timestamps = false;

    protected $fillable = [
        'fecha_entrada',
        'fecha_salida',
        'estado',
        'id_turista',
        'id_habitacion',
        'id_pago',
    ];

    public function turista()
    {
        return $this->belongsTo(Turista::class, 'id_turista');
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class, 'id_habitacion');
    }
}
