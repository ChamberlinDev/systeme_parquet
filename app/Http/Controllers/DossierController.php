<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\Registre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DossierController extends Controller
{
    //affichage des dossiers admin
    public function index()
    {
        $dossiers = Dossier::all();
        return view('admin.dossiers.index', compact('dossiers'));
    }
    // affichage des dossiers greffier
    public function index_greffier()
    {
        $user = Auth::user();

        $dossiers = Dossier::where('id_greffier', $user->id_greffier ?? null)
            ->orWhere('id_parquet', $user->id_parquet ?? null)
            ->get();
        return view('greffier.dossier.index', compact('dossiers'));
    }
    // affichage des dossiers procureur
    public function index_procureur()
    {
        $dossiers = Dossier::all();
        return view('procureur.dossiers.index', compact('dossiers'));
    }
    // affichage des dossiers substitut
    public function index_substitut()
    {
        $dossiers = Dossier::all();
        return view('substitut.dossier.index', compact('dossiers'));
    }
    // affichage des dossiers juge
    public function index_juge()
    {
        $dossiers = Dossier::all();
        return view('juge.dossiers.index', compact('dossiers'));
    }


    public function create_form()
    {
        return view('greffier.dossier.ajout');
    }

    public function create(Request $request)
    {
        $user = auth()->user();

        // Vérifier que l'utilisateur est un greffier ou admin
        if (! $user->hasRole('greffier') && ! $user->hasRole('admin')) {
            abort(403, 'Non autorisé');
        }

        // Récupérer le registre sélectionné
        $registre = Registre::find($request->id_registre);
        if (!$registre) {
            return back()->withErrors(['id_registre' => 'Registre invalide']);
        }

        // Générer le numéro du dossier
        $dernierDossier = Dossier::where('id_registre', $registre->id)->latest('id_dossier')->first();
        $dernierId = $dernierDossier ? $dernierDossier->id_dossier : 0;

        $num_dossier = $registre->code . '/' . date('Y') . '/' . str_pad($dernierId + 1, 3, '0', STR_PAD_LEFT);

        // Créer le dossier
        $dossier = new Dossier();
        $dossier->num_dossier = $num_dossier;
        $dossier->date_enregistrement = $request->date_enregistrement;
        $dossier->type_affaire = $request->type_affaire;
        $dossier->statut = $request->statut;
        $dossier->id_parquet = $request->id_parquet;
        $dossier->id_greffier = $user->id; // l'utilisateur connecté
        $dossier->id_registre = $registre->id;

        $dossier->save();

        return redirect()->route('dossiers.index')->with('success', 'Dossier créé avec succès');
    }
}
