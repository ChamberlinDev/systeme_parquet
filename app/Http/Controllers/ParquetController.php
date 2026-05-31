<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\DossierHistorique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParquetController extends Controller
{
    public function orientationForm(Dossier $dossier)
    {
        $dossier->load(['registre', 'parties', 'files', 'historique.user']);

        return view('procureur.dossier.orientation', compact('dossier'));
    }

    public function orientationStore(Request $request, Dossier $dossier)
    {
        $request->validate([
            'decision_orientation' => 'required|in:classement_sans_suite,citation_directe,comparution_immediate,requisitoire_introductif',
            'motif_orientation'    => 'required|string|min:10',
            'date_orientation'     => 'required|date',
        ]);

        $nouveauStatut = match ($request->decision_orientation) {
            'classement_sans_suite' => 'Classé',
            'requisitoire_introductif' => 'En instruction',
            default => 'Orienté',
        };

        $labels = [
            'classement_sans_suite'   => 'Classement sans suite',
            'citation_directe'        => 'Citation directe',
            'comparution_immediate'   => 'Comparution immédiate',
            'requisitoire_introductif' => 'Réquisitoire introductif',
        ];

        $dossier->update([
            'decision_orientation' => $request->decision_orientation,
            'motif_orientation'    => $request->motif_orientation,
            'date_orientation'     => $request->date_orientation,
            'id_procureur'         => Auth::id(),
            'statut'               => $nouveauStatut,
        ]);

        DossierHistorique::create([
            'id_dossier' => $dossier->id_dossier,
            'user_id'    => Auth::id(),
            'action'     => 'Orientation',
            'detail'     => $labels[$request->decision_orientation] . ' — ' . $request->motif_orientation,
        ]);

        return redirect()
            ->route('dossiers.show', $dossier)
            ->with('success', 'Décision d\'orientation enregistrée.');
    }
}
