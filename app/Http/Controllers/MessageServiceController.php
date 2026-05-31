<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\MessageService;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageServiceController extends Controller
{
    public function index()
    {
        $user           = Auth::user();
        $monService     = MessageService::serviceForUser($user);

        // Reçus : messages adressés à mon service
        $recus = MessageService::with(['expediteur', 'dossier'])
            ->where('service_destinataire', $monService)
            ->latest()
            ->paginate(15, ['*'], 'recus');

        // Envoyés : messages que j'ai expédiés
        $envoyes = MessageService::with(['dossier'])
            ->where('expediteur_id', $user->id)
            ->latest()
            ->paginate(15, ['*'], 'envoyes');

        // Marquer comme lus les messages reçus
        MessageService::where('service_destinataire', $monService)
            ->where('lu', false)
            ->update(['lu' => true]);

        $dossiers = Dossier::orderByDesc('created_at')->limit(100)->get();
        $services = MessageService::$services;

        return view('messagerie.index', compact(
            'recus', 'envoyes', 'dossiers', 'services', 'monService'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_destinataire' => 'required|in:' . implode(',', array_keys(MessageService::$services)),
            'objet'                => 'required|string|max:150',
            'contenu'              => 'required|string|min:3',
            'id_dossier'           => 'nullable|exists:dossiers,id_dossier',
        ]);

        $message = MessageService::create([
            'expediteur_id'        => Auth::id(),
            'service_destinataire' => $request->service_destinataire,
            'objet'                => $request->objet,
            'contenu'              => $request->contenu,
            'id_dossier'           => $request->id_dossier,
            'lu'                   => false,
        ]);

        AuditService::log('MESSAGE_SERVICE', 'MessageService', $message->id,
            'Vers ' . MessageService::$services[$request->service_destinataire]);

        return redirect()->route('messagerie.index')->with('success', 'Message envoyé.');
    }

    /**
     * Compteur de messages non lus pour le service de l'utilisateur courant.
     */
    public static function unreadCount(): int
    {
        $user = Auth::user();
        if (!$user) {
            return 0;
        }
        $service = MessageService::serviceForUser($user);
        if (!$service) {
            return 0;
        }
        return MessageService::where('service_destinataire', $service)
            ->where('lu', false)
            ->count();
    }
}
