<?php

namespace App\Mail;

use App\Models\Execution;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExecutionCreeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Execution $execution) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ordre d\'exécution — ' . ($this->execution->decision->dossier->numero_rp ?? ''),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.execution_cree');
    }
}
