<?php

namespace App\Http\Controllers;

use App\Mail\OrientationDecideeMail;
use App\Models\Dossier;
use App\Models\DossierHistorique;
use App\Models\User;
use App\Services\NotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ParquetController extends Controller
{
    public static array $labels = [
        'classement_sans_suite'    => 'Classement sans suite',
        'citation_directe'         => 'Citation directe',
        'comparution_immediate'    => 'Comparution immédiate',
        'requisitoire_introductif' => 'Réquisitoire introductif',
        'mediation_penale'         => 'Médiation pénale',
        'renvoi_administratif'     => 'Renvoi administratif',
    ];

    public static array $couleurs = [
        'classement_sans_suite'    => 'danger',
        'citation_directe'         => 'primary',
        'comparution_immediate'    => 'warning',
        'requisitoire_introductif' => 'dark',
        'mediation_penale'         => 'success',
        'renvoi_administratif'     => 'secondary',
    ];

    public function orientationForm(Dossier $dossier)
    {
        $dossier->load(['registre', 'parties', 'files', 'historique.user']);

        return view('procureur.dossier.orientation', compact('dossier'));
    }

    public function orientationStore(Request $request, Dossier $dossier)
    {
        $request->validate([
            'decision_orientation' => 'required|in:' . implode(',', array_keys(self::$labels)),
            'motif_orientation'    => 'required|string|min:10',
            'date_orientation'     => 'required|date',
        ]);

        $nouveauStatut = match ($request->decision_orientation) {
            'classement_sans_suite' => 'Classé',
            'requisitoire_introductif' => 'En instruction',
            'mediation_penale'      => 'Médiation',
            'renvoi_administratif'  => 'Classé',
            default                 => 'Orienté',
        };

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
            'detail'     => self::$labels[$request->decision_orientation] . ' — ' . $request->motif_orientation,
        ]);

        // Notifier le greffier du dossier
        if ($dossier->id_greffier) {
            $greffier = User::find($dossier->id_greffier);
            if ($greffier) {
                NotificationService::sendMail(
                    $greffier->email,
                    new OrientationDecideeMail($dossier, self::$labels[$request->decision_orientation])
                );
            }
        }

        return redirect()
            ->route('dossiers.show', $dossier)
            ->with('success', 'Décision d\'orientation enregistrée.');
    }

    public function genererActePdf(Dossier $dossier)
    {
        abort_unless($dossier->decision_orientation, 404, 'Aucune orientation enregistrée.');

        $dossier->load(['registre', 'parties']);
        $procureur = Auth::user();
        $labels    = self::$labels;

        $pdf = Pdf::loadView('pdf.acte_orientation', compact('dossier', 'procureur', 'labels'))
            ->setPaper('a4', 'portrait');

        $filename = 'acte_orientation_' . $dossier->numero_rp . '.pdf';

        return $pdf->download(str_replace('/', '_', $filename));
    }

    public function uploadActeSigne(Request $request, Dossier $dossier)
    {
        $request->validate([
            'acte_signe' => 'required|mimes:pdf|max:5120',
        ]);

        $disk = config('filesystems.default', 'public');
        $disk = $disk === 'local' ? 'public' : $disk;

        if ($dossier->acte_signe_path) {
            try {
                Storage::disk($disk)->delete($dossier->acte_signe_path);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $path = $request->file('acte_signe')
            ->store("dossiers/{$dossier->id_dossier}/actes_signes", $disk);

        $dossier->update(['acte_signe_path' => $path]);

        DossierHistorique::create([
            'id_dossier' => $dossier->id_dossier,
            'user_id'    => Auth::id(),
            'action'     => 'Acte signé déposé',
            'detail'     => 'Acte d\'orientation signé uploadé.',
        ]);

        return back()->with('success', 'Acte signé déposé avec succès.');
    }

    public function voirActeSigne(Dossier $dossier)
    {
        abort_unless($dossier->acte_signe_path, 404);

        $disk = config('filesystems.default', 'public');
        $disk = $disk === 'local' ? 'public' : $disk;

        abort_unless(Storage::disk($disk)->exists($dossier->acte_signe_path), 404);

        return Storage::disk($disk)->response(
            $dossier->acte_signe_path,
            basename($dossier->acte_signe_path),
            ['Content-Type' => 'application/pdf'],
            'inline'
        );
    }
}
