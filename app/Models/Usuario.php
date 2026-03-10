<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\AdminGeneral;
use App\Models\Turista;
use App\Models\AdminDestinos;
use App\Models\GestorRutas;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'correo',
        'google_id',
        'foto_perfil',
        'password',
        'rol',
        'activo',
        'estado',
        'fecha_solicitud',
        'fecha_respuesta',
        'motivo_rechazo',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'activo'          => 'boolean',
        'fecha_solicitud' => 'datetime',
        'fecha_respuesta' => 'datetime',
    ];

    // Le dice a Laravel que el campo login es "correo"
    public function getAuthIdentifierName()
    {
        return 'correo';
    }

    // ================================
    // RELACIONES
    // ================================

    public function turista()
    {
        return $this->hasOne(Turista::class, 'id_usuario', 'id_usuario');
    }

    public function adminDestinos()
    {
        return $this->hasOne(AdminDestinos::class, 'id_usuario', 'id_usuario');
    }

    public function gestorRutas()
    {
        return $this->hasOne(GestorRutas::class, 'id_usuario', 'id_usuario');
    }

    public function adminGeneral()
    {
        return $this->hasOne(AdminGeneral::class, 'id_usuario', 'id_usuario');
    }

    // ================================
    // HELPERS DE ROL
    // ================================

    public function esAdmin()
    {
        return $this->rol === 'admin_general';
    }

    public function esTurista()
    {
        return $this->rol === 'turista';
    }

    public function esAdminDestinos()
    {
        return $this->rol === 'admin_destinos';
    }

    public function esGestorRutas()
    {
        return $this->rol === 'gestor_rutas';
    }

    public function estaAprobado()
    {
        return $this->estado === 'aprobado';
    }

    // Perfil según rol
    public function getPerfilAttribute()
    {
        return match($this->rol) {
            'turista'        => $this->turista,
            'admin_destinos' => $this->adminDestinos,
            'gestor_rutas'   => $this->gestorRutas,
            'admin_general'  => $this->adminGeneral,
            default          => null,
        };
    }
}