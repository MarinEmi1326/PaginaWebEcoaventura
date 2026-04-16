<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'rol';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;

    protected $fillable = ['descripcion'];

    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'persona_rol', 'id_rol', 'id_persona');
    }
}