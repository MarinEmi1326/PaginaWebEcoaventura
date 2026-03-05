<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GestorRutas extends Model
{
    protected $table = 'gestor_rutas';
    protected $primaryKey = 'id_gestor_rutas';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apaterno',
        'amaterno',
        'telefono',
        'id_usuario',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}