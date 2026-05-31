<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\DossierHistorique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArchivageController extends Controller
{
    public function index(Request $request)
    {
        $filtres   = $request->only(['q', 'registre', 'date_du', 'date_au']);
        $registres = \App\Models\Registre::orderBy('nom')->get();

        $query = Dossier::with(['registre', 'parties'])
            ->where('statut', 'Archivé');

        if (!empty($filtres['q'])) {
            $q = $filtres['q'];
            $query->where(function ($sub) use ($q) {
                $sub->where('numero_rp', 'like', "%{$q}%")
                    ->orWhere('numero_registre', 'like', "%{$q}%")
                    ->orWhere('nature_infraction', 'like', "%{$q}%")
                    ->orWhere('motif_archivage', 'like', "%{$q}%")
                    ->orWhereHas('parties', fn($p) =>
                        $p->where('nom', 'like', "%{$q}%")->orWhere('prenom', 'like', "%{$q}%")
                    );
            });
        }

        if (!empty($filtres['registre'])) {
            $query->where('id_registre', $filtres['registre']);
        }

        if (!empty($filtres['date_du'])) {
            $query->whereDate('date_archivage', '>=', $filtres['date_du']);
        }

        if (!empty($filtres['date_au'])) {
            $query->whereDate('date_archivage', '<=', $filtres['date_au']);
        }

        $dossiers = $query->orderByDesc('date_archivage')
            ->paginate(15)
            ->withQueryString();

        return view('greffier.archivage.index', compact('dossiers', 'filtres', 'registres'));
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
