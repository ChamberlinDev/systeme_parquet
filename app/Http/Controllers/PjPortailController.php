<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\DossierHistorique;
use App\Models\PjDocument;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class PjPortailController extends Controller
{
    public function depotForm()
    {
        $dossiers = Dossier::with('registre')
            ->whereNotIn('statut', ['Archivé', 'Classé'])
            ->orderByDesc('created_at')
            ->get();

        $mesDocuments = PjDocument::with('dossier.registre')
            ->where('uploaded_by', Auth::id())
            ->latest()
            ->limit(20)
            ->get();

        return view('externe.pj.depot', compact('dossiers', 'mesDocuments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_dossier'    => 'required|exists:dossiers,id_dossier',
            'type_document' => 'required|in:pv,photo,video,expertise,autre',
            'description'   => 'nullable|string|max:255',
            'fichier'       => 'required|file|max:20480|mimes:pdf,jpg,jpeg,png,mp4,avi,mov,doc,docx',
        ]);

        $disk = config('filesystems.default', 'public');
        $disk = $disk === 'local' ? 'public' : $disk;

        try {
            $file = $request->file('fichier');
            $path = $file->store("dossiers/{$request->id_dossier}/pj", $disk);

            if (!$path) {
                throw new RuntimeException('Le fichier n\'a pas pu être stocké.');
            }

            $document = PjDocument::create([
                'id_dossier'    => $request->id_dossier,
                'uploaded_by'   => Auth::id(),
                'type_document' => $request->type_document,
                'description'   => $request->description,
                'file_path'     => $path,
                'original_name' => $file->getClientOriginalName(),
            ]);
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error',
                'Impossible de déposer le fichier. Vérifie le stockage MinIO.');
        }

        DossierHistorique::create([
            'id_dossier' => $request->id_dossier,
            'user_id'    => Auth::id(),
            'action'     => 'Dépôt PJ',
            'detail'     => PjDocument::$typeLabels[$request->type_document] . ' — ' . ($request->description ?: $document->original_name),
        ]);

        AuditService::log('DEPOT_PJ', 'PjDocument', $document->id,
            PjDocument::$typeLabels[$request->type_document]);

        return redirect()->route('pj.depot.form')->with('success', 'Document déposé avec succès.');
    }

    public function voir(PjDocument $document)
    {
        $disk = config('filesystems.default', 'public');
        $disk = $disk === 'local' ? 'public' : $disk;

        abort_unless(Storage::disk($disk)->exists($document->file_path), 404);

        return Storage::disk($disk)->response(
            $document->file_path,
            $document->original_name ?: basename($document->file_path),
            [],
            'inline'
        );
    }
}
