<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sitio extends Model
{
    protected $table = 'sitio';
    protected $primaryKey = 'id_sitio';
    public $timestamps = false;

    protected $fillable = [
        'nombre','descripcion','direccion','horario','telefono',
        'categoria','comunidad','ciudad','costo','foto','info_guia'
    ];
}
