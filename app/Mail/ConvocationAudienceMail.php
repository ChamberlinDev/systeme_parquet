<?php

namespace App\Mail;

use App\Models\Audience;
use App\Models\Dossier;
use App\Models\Partie;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConvocationAudienceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Audience $audience,
        public readonly Dossier  $dossier,
        public readonly Partie   $partie,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Convocation — Audience du ' . \Carbon\Carbon::parse($this->audience->date_audience)->format('d/m/Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.convocation_audience',
        );
    }
}
