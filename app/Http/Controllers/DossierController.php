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

        $dossiers = Dossier::with(['registre', 'parties', 'files'])
            ->where('id_greffier', $user->id)
            ->paginate(10);
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
        $numbers  = $this->getNextDossierNumber();
        $registres = Registre::all();
        return view('greffier.dossier.ajout', compact('numbers', 'registres'));
    }
    protected function getNextDossierNumber(): array
    {
        $year = date('Y');

        $lastDossier = Dossier::whereYear('created_at', $year)
            ->orderBy('id_dossier', 'desc')
            ->first();

        if ($lastDossier) {
            $parts      = explode('/', $lastDossier->numero_rp);
            $lastNumber = intval(end($parts));
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }

        return [
            'numero_rp'       => "RP/{$year}/{$nextNumber}",
            'numero_registre' => "{$year}/{$nextNumber}",
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_registre'        => 'required|exists:registres,id_registre',
            'nature_infraction'  => 'nullable|string',
            'date_demande'       => 'required|date',
            'parquet_competent'  => 'nullable|string',
            'pdf_files.*'        => 'nullable|mimes:pdf|max:5120',
            'parties.*.nom'      => 'required|string',
            'parties.*.prenom'   => 'nullable|string',
            'parties.*.contact'  => 'nullable|string',
            'parties.*.role'     => 'nullable|string',
        ]);

        $numbers  = $this->getNextDossierNumber();
        $registre = Registre::findOrFail($request->id_registre);
        $seq      = explode('/', $numbers['numero_rp'])[2];
        $year     = date('Y');

        $dossier = Dossier::create([
            'numero_rp'         => $numbers['numero_rp'],
            'numero_registre'   => "{$registre->code}/{$year}/{$seq}",
            'id_registre'       => $registre->id_registre,
            'nature_infraction' => $request->nature_infraction,
            'date_demande'      => $request->date_demande,
            'parquet_competent' => $request->parquet_competent,
            'id_greffier'       => Auth::id(),
        ]);


        // Parties
        if ($request->has('parties')) {
            foreach ($request->parties as $partie) {
                $dossier->parties()->create([
                    'nom'     => $partie['nom'],
                    'prenom'  => $partie['prenom']  ?? null,
                    'contact' => $partie['contact'] ?? null,
                    'role'    => $partie['role']    ?? 'Plaignant',
                ]);
            }
        }

        // Fichiers PDF
        if ($request->hasFile('pdf_files')) {
            foreach ($request->file('pdf_files') as $pdf) {
                $filename = $pdf->store('dossiers_pdfs', 'public');
                $dossier->files()->create(['file_path' => $filename]);
            }
        }

        return redirect()->route('dossiers.index.greffier')
            ->with('success', 'Dossier ajouté avec succès !');
    }
}
