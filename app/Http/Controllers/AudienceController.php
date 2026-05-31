<?php

namespace App\Http\Controllers;

use App\Mail\ConvocationAudienceMail;
use App\Models\Audience;
use App\Models\Dossier;
use App\Models\DossierHistorique;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AudienceController extends Controller
{
    public function indexGreffier()
    {
        $audiences = Audience::with(['dossiers.registre'])
            ->orderByDesc('date_audience')
            ->paginate(10);

        return view('greffier.audience.index', compact('audiences'));
    }

    public function indexJuge()
    {
        $audiences = Audience::with(['dossiers.registre'])
            ->orderByDesc('date_audience')
            ->paginate(10);

        return view('juge.audience.index', compact('audiences'));
    }

    public function indexProcureur()
    {
        $audiences = Audience::with(['dossiers.registre'])
            ->orderByDesc('date_audience')
            ->paginate(10);

        return view('procureur.audience.index', compact('audiences'));
    }

    public function create()
    {
        $dossiers = Dossier::with('registre')
            ->whereNotIn('statut', ['Classé', 'Archive'])
            ->orderByDesc('created_at')
            ->get();

        return view('greffier.audience.ajout', compact('dossiers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_audience'  => 'required|date',
            'salle'          => 'required|string|max:100',
            'type_audience'  => 'required|in:correctionnelle,criminelle,sociale,refere',
            'role'           => 'nullable|string',
            'dossier_ids'    => 'required|array|min:1',
            'dossier_ids.*'  => 'exists:dossiers,id_dossier',
        ]);

        $audience = Audience::create($request->only('date_audience', 'salle', 'type_audience', 'role'));

        $audience->dossiers()->sync($request->dossier_ids);

        $convoquesCount = 0;

        foreach ($request->dossier_ids as $id) {
            DossierHistorique::create([
                'id_dossier' => $id,
                'user_id'    => Auth::id(),
                'action'     => 'Audience planifiée',
                'detail'     => 'Audience du ' . \Carbon\Carbon::parse($request->date_audience)->format('d/m/Y') . ' — Salle : ' . $request->salle,
            ]);
            Dossier::where('id_dossier', $id)->where('statut', 'En cours')->update(['statut' => 'Orienté']);

            // Envoyer convocations aux parties ayant un email valide
            $dossier = Dossier::with('parties')->find($id);
            if ($dossier) {
                foreach ($dossier->parties as $partie) {
                    if ($partie->contact && filter_var($partie->contact, FILTER_VALIDATE_EMAIL)) {
                        NotificationService::sendMail(
                            $partie->contact,
                            new ConvocationAudienceMail($audience, $dossier, $partie)
                        );
                        $convoquesCount++;
                    }
                    // SMS stub si contact ressemble à un numéro
                    if ($partie->contact && !filter_var($partie->contact, FILTER_VALIDATE_EMAIL)) {
                        NotificationService::sendSms(
                            $partie->contact,
                            "Convocation : vous êtes convoqué(e) le " .
                            \Carbon\Carbon::parse($request->date_audience)->format('d/m/Y') .
                            " salle {$request->salle} — Dossier {$dossier->numero_registre}"
                        );
                    }
                }
            }
        }

        $msg = 'Audience planifiée avec succès.';
        if ($convoquesCount > 0) {
            $msg .= " {$convoquesCount} convocation(s) email envoyée(s).";
        }

        return redirect()->route('audiences.index.greffier')->with('success', $msg);
    }

    public function show(Audience $audience)
    {
        $audience->load(['dossiers.registre', 'dossiers.parties', 'dossiers.decisions']);

        return view('audiences.show', compact('audience'));
    }

    public function addPv(Request $request, Audience $audience)
    {
        $request->validate([
            'pv' => 'required|string|min:10',
        ]);

        $audience->update(['pv' => $request->pv]);

        foreach ($audience->dossiers as $dossier) {
            DossierHistorique::create([
                'id_dossier' => $dossier->id_dossier,
                'user_id'    => Auth::id(),
                'action'     => 'PV d\'audience enregistré',
                'detail'     => 'Audience du ' . \Carbon\Carbon::parse($audience->date_audience)->format('d/m/Y'),
            ]);
        }

        return redirect()->route('audiences.show', $audience)
            ->with('success', 'Procès-verbal enregistré.');
    }
}
