<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminDestinos extends Model
{
    protected $table = 'admin_destinos';
    protected $primaryKey = 'id_admin_destinos';
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