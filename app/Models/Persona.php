<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'persona';
    protected $primaryKey = 'id_persona';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'nombre',
        'apellidos',
        'telefono'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'persona_rol', 'id_persona', 'id_rol');
    }
    public function tieneRol($rol)
    {
        return $this->roles()->where('descripcion', $rol)->exists();
    }
}
