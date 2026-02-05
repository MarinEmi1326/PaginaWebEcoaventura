<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Turista;
use App\Models\Hotelero;
use App\Models\Restaurantero;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'correo',
        'password',
        'rol',
        'activo',
        'estado',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * 👇 Le dice a Laravel que el campo de login es "correo"
     * y no "email"
     */
    public function getAuthIdentifierName()
    {
        return 'correo';
    }

    /**
     * Relación con el modelo Turista.
     * Un usuario puede ser turista (solo si tiene el rol 'turista')
     */
    public function turista()
    {
        return $this->hasOne(Turista::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Relación con el modelo Hotelero.
     * Un usuario puede ser hotelero (solo si tiene el rol 'hotelero')
     */
    public function hotelero()
    {
        return $this->hasOne(Hotelero::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Relación con el modelo Restaurantero.
     * Un usuario puede ser restaurantero (solo si tiene el rol 'restaurantero')
     */
    public function restaurantero()
    {
        return $this->hasOne(Restaurantero::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Acceder al perfil dependiendo del rol del usuario.
     */
    public function getPerfilAttribute()
    {
        switch ($this->rol) {
            case 'turista':
                return $this->turista;
            case 'hotelero':
                return $this->hotelero;
            case 'restaurantero':
                return $this->restaurantero;
            default:
                return null;
        }
    }
}
