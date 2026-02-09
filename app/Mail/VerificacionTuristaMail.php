<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificacionTuristaMail extends Mailable
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
            subject: 'Verificacion Turista Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // AQUÍ CAMBIAMOS 'view.name' por tu vista real
            view: 'email.verificacion_turista',
            with: [
                'nombre' => $this->user->nombre,
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
