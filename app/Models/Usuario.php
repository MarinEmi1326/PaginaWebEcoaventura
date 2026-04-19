<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'correo', 'correo_verificado', 'token_verificacion', 'google_id',
        'foto_perfil', 'password', 'activo', 'estado', 'fecha_solicitud',
        'fecha_respuesta', 'motivo_rechazo', 'fcm_token'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'activo'             => 'boolean',
        'correo_verificado'  => 'boolean',
        'fecha_solicitud'    => 'datetime',
        'fecha_respuesta'    => 'datetime',
    ];

    public function getAuthIdentifierName() { return 'id_usuario'; }

    public function persona()
    {
        return $this->hasOne(Persona::class, 'id_usuario', 'id_usuario');
    }
}