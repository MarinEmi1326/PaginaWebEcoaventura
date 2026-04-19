<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmacionPagoTurista extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $pago,
        public $paquete,
        public $destino,
        public $persona
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Confirmación de pago - ' . $this->paquete->nombre,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.pago-turista',
        );
    }
}