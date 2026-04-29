<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\Registre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            ->where('parquet_id', $user->parquet_id)
            ->where('id_greffier', $user->id)
            ->latest()
            ->paginate(10);

        return view('greffier.dossier.index', compact('dossiers'));
    }

    public function create_form()
    {
        $registres = Registre::all();
        $dossiers = Dossier::where('id_greffier', Auth::id());

        // valeur par défaut (ex: premier registre)
        $defaultRegistre = $registres->first();

        $numbers = null;

        if ($defaultRegistre) {
            $data = $this->getNextDossierNumber($defaultRegistre->code);

            $numbers = [
                'numero_rp' => "RP/{$data['year']}/{$data['sequence']}",
                'numero_registre' => "{$defaultRegistre->code}/{$data['year']}/{$data['sequence']}"
            ];
        }

        return view('greffier.dossier.ajout', compact('numbers', 'registres', 'dossiers'));
    }

    protected function getNextDossierNumber($registreCode): array
    {
        $year      = date('Y');
        $parquetId = Auth::user()->parquet_id;

        $lastDossier = Dossier::whereYear('created_at', $year)
            ->where('parquet_id', $parquetId)
            ->whereHas('registre', function ($q) use ($registreCode) {
                $q->where('code', $registreCode);
            })
            ->orderBy('id_dossier', 'desc')
            ->lockForUpdate()
            ->first();

        if ($lastDossier) {
            preg_match("/{$registreCode}\/\d{4}\/(\d+)/", $lastDossier->numero_registre, $matches);
            $lastNumber = isset($matches[1]) ? intval($matches[1]) : 0;
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }

        return [
            'sequence' => $nextNumber,
            'year'     => $year
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_registre'        => 'required|exists:registres,id_registre',
            'nature_infraction'  => 'nullable|string',
            'date_demande'       => 'required|date',
            'pdf_files.*'        => 'nullable|mimes:pdf|max:5120',
            'parties.*.nom'      => 'required|string',
            'parties.*.prenom'   => 'nullable|string',
            'parties.*.contact'  => 'nullable|string',
            'parties.*.role'     => 'nullable|string',
        ]);

        $dossier = DB::transaction(function () use ($request) {

            $registre = Registre::findOrFail($request->id_registre);

            $numbers = $this->getNextDossierNumber($registre->code);

            $numero_registre = "{$registre->code}/{$numbers['year']}/{$numbers['sequence']}";
            $numero_rp       = "RP/{$numbers['year']}/{$numbers['sequence']}";

            $dossier = Dossier::create([
                'numero_rp'         => $numero_rp,
                'numero_registre'   => $numero_registre,
                'id_registre'       => $registre->id_registre,
                'nature_infraction' => $request->nature_infraction,
                'date_demande'      => $request->date_demande,
                'parquet_id'        => Auth::user()->parquet_id,
                'id_greffier'       => Auth::id(),
            ]);

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

            if ($request->hasFile('pdf_files')) {
                foreach ($request->file('pdf_files') as $pdf) {
                    $filename = $pdf->store('dossiers_pdfs', 'public');
                    $dossier->files()->create(['file_path' => $filename]);
                }
            }

            return $dossier;
        });

        return redirect()->route('dossiers.index.greffier')
            ->with('success', "Dossier {$dossier->numero_rp} ajouté avec succès !");
    }


    public function show($id)
    {
        $dossier = Dossier::with(['registre', 'parties', 'files', 'parquet'])
            ->where('id_greffier', Auth::id())
            ->findOrFail($id);

        return view('greffier.dossier.details', compact('dossier'));
    }

    public function edit($id)
    {
        //
        $dossier = Dossier::with(['registre', 'parties', 'files'])
            ->where('id_greffier', Auth::id())
            ->findOrFail($id);

        $registres = Registre::all();

        return view('greffier.dossier.modif', compact('dossier', 'registres'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_registre'       => 'required|exists:registres,id_registre',
            'nature_infraction' => 'nullable|string',
            'date_demande'      => 'required|date',
            'pdf_files.*'       => 'nullable|mimes:pdf|max:5120',
            'parties.*.nom'     => 'required|string',
            'parties.*.prenom'  => 'nullable|string',
            'parties.*.contact' => 'nullable|string',
            'parties.*.role'    => 'nullable|string',
        ]);

        $dossier = Dossier::where('id_greffier', Auth::id())->findOrFail($id);

        $dossier->update([
            'id_registre'       => $request->id_registre,
            'nature_infraction' => $request->nature_infraction,
            'date_demande'      => $request->date_demande,
        ]);

        // Sync parties
        $dossier->parties()->delete();
        foreach ($request->parties ?? [] as $partie) {
            $dossier->parties()->create([
                'nom'     => $partie['nom'],
                'prenom'  => $partie['prenom']  ?? null,
                'contact' => $partie['contact'] ?? null,
                'role'    => $partie['role']    ?? 'Plaignant',
            ]);
        }

        // Supprimer fichiers cochés
        if ($request->has('delete_files')) {
            foreach ($request->delete_files as $fileId) {
                $file = $dossier->files()->findOrFail($fileId);
                Storage::disk('public')->delete($file->file_path);
                $file->delete();
            }
        }

        // Nouveaux fichiers
        if ($request->hasFile('pdf_files')) {
            foreach ($request->file('pdf_files') as $pdf) {
                $filename = $pdf->store('dossiers_pdfs', 'public');
                $dossier->files()->create(['file_path' => $filename]);
            }
        }

        return redirect()->route('dossiers.index.greffier')
            ->with('success', "Dossier {$dossier->numero_rp} modifié avec succès !");
    }


    public function destroy($id)
    {
        //suppression d'un dossier
        $dossier = Dossier::where('id_greffier', Auth::id())->findOrFail($id); 
        foreach ($dossier->files as $file) {
            Storage::disk('public')->delete($file->file_path);
        }
        $dossier->delete();
        return redirect()->route('dossiers.index.greffier')
            ->with('success', "Dossier {$dossier->numero_rp} supprimé avec succès !");

    }
}
