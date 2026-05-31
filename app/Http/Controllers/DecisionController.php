<?php

namespace App\Http\Controllers;

use App\Models\Audience;
use App\Models\Decision;
use App\Models\Dossier;
use App\Models\DossierHistorique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DecisionController extends Controller
{
    public function create(Audience $audience, Dossier $dossier)
    {
        $audience->load('dossiers.registre');
        abort_unless($audience->dossiers->contains('id_dossier', $dossier->id_dossier), 404);

        $existingDecision = Decision::where('id_audience', $audience->id_audience)
            ->where('id_dossier', $dossier->id_dossier)
            ->first();

        return view('juge.audience.decision', compact('audience', 'dossier', 'existingDecision'));
    }

    public function store(Request $request, Audience $audience, Dossier $dossier)
    {
        abort_unless($audience->dossiers->contains('id_dossier', $dossier->id_dossier), 404);

        $request->validate([
            'type_decision' => 'required|in:jugement,ordonnance,arret',
            'contenu'       => 'required|string|min:20',
            'date_decision' => 'required|date',
        ]);

        $decision = Decision::updateOrCreate(
            ['id_audience' => $audience->id_audience, 'id_dossier' => $dossier->id_dossier],
            [
                'type_decision' => $request->type_decision,
                'contenu'       => $request->contenu,
                'date_decision' => $request->date_decision,
                'signatures'    => Auth::user()->name,
            ]
        );

        $dossier->update(['statut' => 'Jugé']);

        DossierHistorique::create([
            'id_dossier' => $dossier->id_dossier,
            'user_id'    => Auth::id(),
            'action'     => 'Décision judiciaire',
            'detail'     => ucfirst($request->type_decision) . ' rendu(e) le ' . \Carbon\Carbon::parse($request->date_decision)->format('d/m/Y'),
        ]);

        return redirect()->route('audiences.show', $audience)
            ->with('success', 'Décision enregistrée pour le dossier ' . $dossier->numero_registre . '.');
    }
}
