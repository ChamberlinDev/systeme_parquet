<?php

namespace App\Services;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Envoie un email en silence : log l'erreur sans crasher si le mail est mal configuré.
     */
    public static function sendMail(string|array $to, Mailable $mailable): void
    {
        try {
            $recipients = is_array($to) ? array_filter($to) : [$to];
            $recipients = array_filter($recipients, fn($r) => filter_var($r, FILTER_VALIDATE_EMAIL));

            if (empty($recipients)) {
                return;
            }

            Mail::to($recipients)->send($mailable);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Stub SMS — nécessite un provider externe (Twilio, Nexmo, Orange SMS...).
     * Logue le message pour l'instant.
     */
    public static function sendSms(string $numero, string $message): void
    {
        $numero = preg_replace('/\D/', '', $numero);

        if (strlen($numero) < 8) {
            return;
        }

        // TODO: intégrer un provider SMS (ex: Twilio)
        // \Twilio\Rest\Client::messages->create($numero, ['from' => config('sms.from'), 'body' => $message]);

        Log::channel('stack')->info("SMS (stub) → +{$numero}: {$message}");
    }
}
