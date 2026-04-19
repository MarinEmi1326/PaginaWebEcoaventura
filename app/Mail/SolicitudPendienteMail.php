<?php

namespace App\Mail;

use App\Models\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SolicitudPendienteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $nombrePersona;

    public function __construct(Usuario $usuario)
    {
        $this->usuario = $usuario;
        // Cargamos el nombre de la persona de forma segura
        $this->nombrePersona = $usuario->persona?->nombre ?? 'Usuario';
    }

    public function build()
    {
        return $this->view('email.solicitud_pendiente')
                    ->subject('Solicitud en Espera - EcoAventura')
                    ->with([
                        'nombre' => $this->nombrePersona,
                        'correo' => $this->usuario->correo,
                        'token'  => $this->usuario->token_verificacion,
                    ]);
    }
}