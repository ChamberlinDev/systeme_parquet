<?php

namespace App\Mail;

use App\Models\Dossier;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrientationDecideeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Dossier $dossier,
        public readonly string  $labelOrientation,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Décision d\'orientation — ' . $this->dossier->numero_rp,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.orientation_decidee');
    }
}
