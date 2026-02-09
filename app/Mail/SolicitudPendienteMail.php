<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SolicitudPendienteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Crear una nueva instancia de mensaje.
     *
     * @param  \App\Models\Usuario  $user
     * @return void
     */
    public function __construct($user)
    {
         $this->user = $user;
    }

    /**
     * Construir el mensaje.
     *
     * @return $this
     */
    
    
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Solicitud Recibida - En Espera de Aprobación',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // 1. Quitamos 'view.name' y ponemos tu ruta real
            view: 'email.solicitud_pendiente', 
            with: [
                // 2. Usamos el accesor perfil->nombre para no tener errores
                'nombre' => $this->user->perfil->nombre,
                'correo' => $this->user->correo,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
