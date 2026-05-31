<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\DossierHistorique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArchivageController extends Controller
{
    public function index()
    {
        $dossiers = Dossier::with(['registre', 'parties'])
            ->where('statut', 'Archivé')
            ->orderByDesc('date_archivage')
            ->paginate(15);

        return view('greffier.archivage.index', compact('dossiers'));
    }

    public function confirmer(Dossier $dossier)
    {
        $statutsArchivables = ['Exécuté', 'Classé', 'Jugé'];

        abort_unless(in_array($dossier->statut, $statutsArchivables), 403,
            'Ce dossier ne peut pas être archivé dans son statut actuel.');

        return view('greffier.archivage.confirmer', compact('dossier'));
    }

    public function store(Request $request, Dossier $dossier)
    {
        $request->validate([
            'motif_archivage' => 'required|string|min:10',
            'date_archivage'  => 'required|date',
        ]);

        $dossier->update([
            'statut'          => 'Archivé',
            'motif_archivage' => $request->motif_archivage,
            'date_archivage'  => $request->date_archivage,
        ]);

        DossierHistorique::create([
            'id_dossier' => $dossier->id_dossier,
            'user_id'    => Auth::id(),
            'action'     => 'Archivage',
            'detail'     => $request->motif_archivage,
        ]);

        return redirect()->route('archivage.index')
            ->with('success', 'Dossier ' . $dossier->numero_registre . ' archivé.');
    }
}
