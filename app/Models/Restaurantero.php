<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurantero extends Model
{
    protected $table = 'restaurantero';
    protected $primaryKey = 'id_restaurantero';
    public $timestamps = false;

    protected $fillable = ['nombre','apaterno','amaterno','telefono','id_usuario'];
}
