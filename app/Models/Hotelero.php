<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotelero extends Model
{
    protected $table = 'hotelero';
    protected $primaryKey = 'id_hotelero';
    public $timestamps = false;

    protected $fillable = ['nombre','apaterno','amaterno','telefono','id_usuario'];
}

