<?php

namespace App\Notifications;

use App\Models\Dossier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DossierTransmisNotification extends Notification
{
use Queueable;

    public function __construct(public Dossier $dossier) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Nouveau dossier transmis — {$this->dossier->numero_rp}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Un nouveau dossier vous a été transmis par le greffier **{$this->dossier->greffier->name}**.")
            ->line("**Numéro RP :** {$this->dossier->numero_rp}")
            ->line("**Numéro Registre :** {$this->dossier->numero_registre}")
            ->line("**Type d'affaire :** {$this->dossier->registre->nom}")
            ->line("**Date de la demande :** {$this->dossier->date_demande}")
            ->when($this->dossier->nature_infraction, fn($mail) =>
                $mail->line("**Nature de l'infraction :** {$this->dossier->nature_infraction}")
            )
            ->when($this->dossier->motif_orientation, fn($mail) =>
                $mail->line("**Motif du transfert :** {$this->dossier->motif_orientation}")
            )
            ->action('Voir le dossier', url('/greffier/dossiers/' . $this->dossier->id_dossier))
            ->line('Merci de traiter ce dossier dans les meilleurs délais.')
            ->salutation("Cordialement, le Système de Gestion des Dossiers Judiciaires");
    }

}
