<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactoAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $email;
    public $contenido;

    /**
     * Create a new message instance.
     */
    public function __construct($nombre, $email, $contenido)
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->contenido = $contenido;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Contacto Admin Mail',
        );
    }

    public function build()
    {
        return $this->subject('Nuevo mensaje desde contacto')
                    ->view('emails.contacto_admin');
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
