<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SolicitudAprobadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $nombreCompleto;

    public function __construct($usuario, $nombreCompleto)
    {
        $this->usuario = $usuario;
        $this->nombreCompleto = $nombreCompleto;
    }

    public function build()
    {
        return $this->view('email.solicitud_aprobada')
                    ->with([
                        'nombre' => $this->nombreCompleto,
                        'correo' => $this->usuario->correo
                    ]);
    }
}