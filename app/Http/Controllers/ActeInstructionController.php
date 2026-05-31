<?php

namespace App\Http\Controllers;

use App\Models\ActeInstruction;
use App\Models\DossierHistorique;
use App\Models\Instruction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActeInstructionController extends Controller
{
    public function store(Request $request, Instruction $instruction)
    {
        abort_unless($instruction->isEnCours(), 403, 'Instruction clôturée.');

        $request->validate([
            'type_acte'     => 'required|in:commission_rogatoire,expertise,audition,confrontation,perquisition,autre',
            'objet'         => 'required|string|min:5',
            'date_demande'  => 'required|date',
            'date_echeance' => 'nullable|date|after_or_equal:date_demande',
        ]);

        $instruction->actes()->create([
            'id_demandeur'  => Auth::id(),
            'type_acte'     => $request->type_acte,
            'objet'         => $request->objet,
            'date_demande'  => $request->date_demande,
            'date_echeance' => $request->date_echeance,
            'statut'        => 'en_attente',
        ]);

        DossierHistorique::create([
            'id_dossier' => $instruction->id_dossier,
            'user_id'    => Auth::id(),
            'action'     => 'Acte d\'instruction ajouté',
            'detail'     => ucfirst(str_replace('_', ' ', $request->type_acte)) . ' — ' . $request->objet,
        ]);

        return back()->with('success', 'Acte d\'instruction enregistré.');
    }

    public function updateStatut(Request $request, ActeInstruction $acte)
    {
        $request->validate([
            'statut'         => 'required|in:en_attente,en_cours,execute,annule',
            'resultat'       => 'nullable|string',
            'date_execution' => 'nullable|date',
        ]);

        $acte->update([
            'statut'         => $request->statut,
            'resultat'       => $request->resultat,
            'date_execution' => $request->date_execution,
        ]);

        return back()->with('success', 'Statut de l\'acte mis à jour.');
    }
}
