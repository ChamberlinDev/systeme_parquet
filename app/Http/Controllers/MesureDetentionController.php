<?php

namespace App\Http\Controllers;

use App\Models\DossierHistorique;
use App\Models\Instruction;
use App\Models\MesureDetention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MesureDetentionController extends Controller
{
    public function store(Request $request, Instruction $instruction)
    {
        abort_unless($instruction->isEnCours(), 403, 'Instruction clôturée.');

        $request->validate([
            'date_debut'    => 'required|date',
            'date_echeance' => 'required|date|after:date_debut',
            'motif'         => 'required|string|min:10',
        ]);

        $instruction->detentions()->create([
            'date_debut'    => $request->date_debut,
            'date_echeance' => $request->date_echeance,
            'motif'         => $request->motif,
            'statut'        => 'en_cours',
        ]);

        DossierHistorique::create([
            'id_dossier' => $instruction->id_dossier,
            'user_id'    => Auth::id(),
            'action'     => 'Détention provisoire enregistrée',
            'detail'     => 'Échéance : ' . \Carbon\Carbon::parse($request->date_echeance)->format('d/m/Y'),
        ]);

        return back()->with('success', 'Mesure de détention enregistrée.');
    }

    public function prolonger(Request $request, MesureDetention $detention)
    {
        $request->validate([
            'date_echeance' => 'required|date|after:' . $detention->date_echeance->toDateString(),
            'motif'         => 'required|string|min:10',
        ]);

        $detention->update([
            'date_echeance' => $request->date_echeance,
            'statut'        => 'prolongee',
            'motif'         => $detention->motif . "\n[Prolongation] " . $request->motif,
        ]);

        DossierHistorique::create([
            'id_dossier' => $detention->instruction->id_dossier,
            'user_id'    => Auth::id(),
            'action'     => 'Détention prolongée',
            'detail'     => 'Nouvelle échéance : ' . \Carbon\Carbon::parse($request->date_echeance)->format('d/m/Y'),
        ]);

        return back()->with('success', 'Détention prolongée.');
    }

    public function lever(MesureDetention $detention)
    {
        $detention->update(['statut' => 'levee']);

        DossierHistorique::create([
            'id_dossier' => $detention->instruction->id_dossier,
            'user_id'    => Auth::id(),
            'action'     => 'Détention levée',
            'detail'     => 'Mise en liberté ordonnée.',
        ]);

        return back()->with('success', 'Détention levée.');
    }
}
