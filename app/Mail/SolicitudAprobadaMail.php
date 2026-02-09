<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SolicitudAprobadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;

    // Recibimos solo el nombre para que sea más ligero
    public function __construct($usuario)
    {
        $this->usuario = $usuario;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Bienvenido! Tu solicitud en Ecoaventura ha sido aprobada',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.solicitud_aprobada', // Esta es la vista que crearemos en el paso 2
            with: [
                'nombre' => $this->usuario->nombre_persona,
                'correo' => $this->usuario->correo
            ],
        );
    }
}