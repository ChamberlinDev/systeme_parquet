<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\Registre;
use App\Models\User;
use App\Notifications\DossierTransmisNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $dossiers  = Dossier::where('id_greffier', Auth::id());

        $defaultRegistre = $registres->first();
        $numbers = null;

        if ($defaultRegistre) {
            $data = $this->getNextDossierNumber($defaultRegistre->code);

            $numbers = [
                'numero_rp'       => "RP/{$data['year']}/{$data['sequence']}",
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
            'year'     => $year,
        ];
    }

    protected function getNextRpNumber($parquetId): string
    {
        $year = date('Y');

        $last = Dossier::whereYear('created_at', $year)
            ->where('parquet_id', $parquetId)
            ->where('numero_rp', 'like', "RP/{$year}/%")
            ->orderByRaw('CAST(SUBSTRING_INDEX(numero_rp, "/", -1) AS UNSIGNED) DESC')
            ->lockForUpdate()
            ->first();

        if ($last) {
            preg_match("/RP\/\d{4}\/(\d+)/", $last->numero_rp, $matches);
            $lastNumber = isset($matches[1]) ? (int)$matches[1] : 0;
            $next       = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $next = '001';
        }

        return "RP/{$year}/{$next}";
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_registre'        => 'required|exists:registres,id_registre',
            'nature_infraction'  => 'nullable|string',
            'date_demande'       => 'required|date',
            'pdf_files.*'        => 'nullable|mimes:pdf|max:5120',
            'parties.*.nom'      => 'required|string',
        ]);

        $parquetId = Auth::user()->parquet_id;
        $dossier   = null;
        $attempts  = 0;

        while ($attempts < 3) {
            try {
                $dossier = DB::transaction(function () use ($request, $parquetId) {

                    $registre        = Registre::findOrFail($request->id_registre);
                    $numbers         = $this->getNextDossierNumber($registre->code);
                    $numero_registre = "{$registre->code}/{$numbers['year']}/{$numbers['sequence']}";
                    $numero_rp       = $this->getNextRpNumber($parquetId);

                    $dossier = Dossier::create([
                        'numero_rp'         => $numero_rp,
                        'numero_registre'   => $numero_registre,
                        'id_registre'       => $registre->id_registre,
                        'nature_infraction' => $request->nature_infraction,
                        'date_demande'      => $request->date_demande,
                        'parquet_id'        => $parquetId,
                        'id_greffier'       => Auth::id(),
                        'procureur_id'      => $request->procureur_id ?? null,
                        'statut'            => $request->filled('procureur_id') ? 'Orienté' : 'En cours',
                        'motif_orientation' => $request->motif_orientation ?? null,
                        'date_orientation'  => $request->filled('procureur_id') ? now() : null,
                    ]);

                    // Parties
                    foreach ($request->parties ?? [] as $partie) {
                        $dossier->parties()->create([
                            'nom'     => $partie['nom'],
                            'prenom'  => $partie['prenom']  ?? null,
                            'contact' => $partie['contact'] ?? null,
                            'role'    => $partie['role']    ?? 'Plaignant',
                        ]);
                    }

                    // Fichiers
                    if ($request->hasFile('pdf_files')) {
                        foreach ($request->file('pdf_files') as $pdf) {
                            $filename = $pdf->store('dossiers_pdfs', 'public');
                            $dossier->files()->create(['file_path' => $filename]);
                        }
                    }

                    return $dossier;
                });

                break;
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->errorInfo[1] == 1062) {
                    $attempts++;
                    continue;
                }
                throw $e;
            }
        }

        if (!$dossier) {
            return back()->withErrors(['error' => 'Erreur lors de la création, veuillez réessayer.']);
        }

        // Notifier le procureur si un transfert a été fait
        if ($request->filled('procureur_id')) {
            $procureur = User::find($request->procureur_id);
            if ($procureur) {
                $dossier->load(['registre', 'greffier']);
                $procureur->notify(new DossierTransmisNotification($dossier));
            }
        }

        return redirect()->route('dossiers.index.greffier')
            ->with('success', "Dossier {$dossier->numero_rp} ajouté avec succès !");
    }

    public function orienter(Request $request, $id)
    {
        $request->validate([
            'statut'            => 'required|in:En cours,Clôturé,Archivé,Suspendu,Orienté',
            'procureur_id'      => 'nullable|exists:users,id',
            'motif_orientation' => 'nullable|string|max:500',
        ]);

        $dossier = Dossier::where('id_greffier', Auth::id())->findOrFail($id);

        $ancienProcureurId = $dossier->procureur_id;

        $dossier->update([
            'statut'            => $request->statut,
            'procureur_id'      => $request->procureur_id,
            'motif_orientation' => $request->motif_orientation,
            'date_orientation'  => now(),
        ]);

        // Notifier le nouveau procureur s'il a changé
        if ($request->filled('procureur_id') && $request->procureur_id != $ancienProcureurId) {
            $procureur = User::find($request->procureur_id);
            if ($procureur) {
                try {
                    $dossier->load(['registre', 'greffier']);
                    $procureur->notify(new DossierTransmisNotification($dossier));
                    $messageEmail = " — Email de notification envoyé à {$procureur->name}.";
                } catch (\Exception $e) {
                    // L'email a échoué mais le dossier est bien orienté
                    Log::error("Échec envoi email orientation dossier {$dossier->numero_rp} : " . $e->getMessage());
                    $messageEmail = " — (notification email non envoyée)";
                }
            }
        }

        return redirect()->back()->with('success', "Dossier orienté avec succès !" . ($messageEmail ?? ''));
    }

    public function show($id)
    {
        $dossier = Dossier::with(['registre', 'parties', 'files', 'parquet', 'procureur'])
            ->where('id_greffier', Auth::id())
            ->findOrFail($id);

        $procureurs = User::role('procureur')
            ->where('parquet_id', Auth::user()->parquet_id)
            ->get();

        return view('greffier.dossier.details', compact('dossier', 'procureurs'));
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
