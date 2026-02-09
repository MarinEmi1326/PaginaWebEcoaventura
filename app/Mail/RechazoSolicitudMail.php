<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RechazoSolicitudMail extends Mailable
{
    use Queueable, SerializesModels;

    // Declaramos las variables que definiste en el constructor
    public $motivo_rechazo;
    public $nombre;

    public function __construct($motivo_rechazo, $nombre)
    {
        $this->motivo_rechazo = $motivo_rechazo;
        $this->nombre = $nombre;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Actualización de tu solicitud - Ecoaventura',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.solicitud_rechazada',
            with: [
                // USAMOS las variables que ya tenemos en la clase ($this->...)
                'motivo' => $this->motivo_rechazo,
                'nombre' => $this->nombre,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}