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
        $dossiers = Dossier::latest()->paginate(10);
        return view('admin.dossiers.index', compact('dossiers'));
    }
    // affichage des dossiers greffier
    public function index_greffier()
    {
        $user = Auth::user();

        $dossiers = Dossier::where('id_greffier', $user->id)->paginate(10);

        return view('greffier.dossier.index', compact('dossiers'));
    }

    // affichage des dossiers procureur
    // public function index_procureur()
    // {
    //     $dossiers = Dossier::all();
    //     return view('procureur.dossiers.index', compact('dossiers'));
    // }
    // affichage des dossiers substitut
    // public function index_substitut()
    // {
    //     $dossiers = Dossier::all();
    //     return view('substitut.dossier.index', compact('dossiers'));
    // }
    // affichage des dossiers juge
    // public function index_juge()
    // {
    //     $dossiers = Dossier::all();
    //     return view('juge.dossiers.index', compact('dossiers'));
    // }


    public function create_form()
    {
        $nextDossierRP = $this->getNextDossierNumber();
        return view('greffier.dossier.ajout', compact('nextDossierRP'));
    }

    protected function getNextDossierNumber()
    {
        $year = date('Y');

        // On récupère le dernier dossier de l'année en cours
        $lastDossier = Dossier::whereYear('created_at', $year)
            ->orderBy('id_dossier', 'desc')
            ->first();

        if ($lastDossier) {
            $parts = explode('/', $lastDossier->registre_rp);
            $lastNumber = intval(end($parts)); // Convertir en entier
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }

        return "RP/{$year}/{$nextNumber}";
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_affaire' => 'required|string',
            'date_demande' => 'required|date',
            'pdf_files.*' => 'nullable|mimes:pdf|max:5120', // 5MB max
            'parties.*.nom' => 'required|string',
            'parties.*.prenom' => 'nullable|string',
            'parties.*.contact' => 'nullable|string',
        ]);

        // Création du dossier
        $dossier = Dossier::create([
            'registre_rp' => $request->registre_rp,
            'type_affaire' => $request->type_affaire,
            'date_demande' => $request->date_demande,
            'id_greffier' => auth()->id(), // ou auth()->user()->id_greffier si tu as ce champ
        ]);

        // Gestion des parties
        if ($request->has('parties')) {
            foreach ($request->parties as $partie) {
                $dossier->parties()->create([
                    'nom' => $partie['nom'],
                    'prenom' => $partie['prenom'] ?? null,
                    'contact' => $partie['contact'] ?? null,
                    'role' => 'Plaignant',
                ]);
            }
        }

        // Gestion des fichiers PDF
        if ($request->hasFile('pdf_files')) {
            foreach ($request->file('pdf_files') as $pdf) {
                $filename = $pdf->store('dossiers_pdfs', 'public');
                $dossier->files()->create(['file_path' => $filename]);
            }
        }

        return redirect()->route('dossiers.index.greffier')->with('success', 'Dossier ajouté avec succès !');
    }
}
