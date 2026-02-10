<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicio_catalogo';
    protected $primaryKey = 'id_servicio';
    public $timestamps = false;

    protected $fillable = ['nombre'];

    // Relación con Hoteles (Muchos a Muchos)
    public function hoteles()
    {
        return $this->belongsToMany(Hotel::class, 'hotel_servicio', 'id_servicio', 'id_hotel');
    }
}