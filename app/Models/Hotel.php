<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $table = 'hotel';
    protected $primaryKey = 'id_hotel';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 
        'direccion',
        'descripcion', 
        'telefono', 
        'foto', 
        'id_hotelero'];
    
    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'hotel_servicio', 'id_hotel', 'id_servicio');
    }
}