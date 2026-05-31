<?php

namespace App\Http\Controllers;

use App\Models\Decision;
use App\Models\Dossier;
use App\Models\Execution;
use App\Services\AuditService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ActePdfController extends Controller
{
    private function pdf(string $view, array $data, string $filename): \Illuminate\Http\Response
    {
        $pdf = Pdf::loadView($view, $data)->setPaper('a4', 'portrait');
        AuditService::log('GENERATION_PDF', null, null, $filename);
        return $pdf->download($filename);
    }

    public function citation(Dossier $dossier)
    {
        abort_unless($dossier->decision_orientation === 'citation_directe', 403,
            'Ce dossier n\'a pas de citation directe.');

        $dossier->load(['registre', 'parties']);
        $procureur = Auth::user();

        return $this->pdf(
            'pdf.citation_directe',
            compact('dossier', 'procureur'),
            'citation_directe_' . str_replace('/', '_', $dossier->numero_rp) . '.pdf'
        );
    }

    public function requisitoire(Dossier $dossier)
    {
        abort_unless($dossier->decision_orientation === 'requisitoire_introductif', 403,
            'Ce dossier n\'a pas de réquisitoire introductif.');

        $dossier->load(['registre', 'parties', 'instructions.juge']);
        $procureur = Auth::user();

        return $this->pdf(
            'pdf.requisitoire_introductif',
            compact('dossier', 'procureur'),
            'requisitoire_' . str_replace('/', '_', $dossier->numero_rp) . '.pdf'
        );
    }

    public function mandat(Execution $execution)
    {
        abort_unless($execution->type_peine === 'privative_liberte', 403,
            'Ce mandat concerne une peine privative de liberté uniquement.');

        $execution->load(['decision.dossier.registre', 'decision.dossier.parties', 'institution']);
        $dossier = $execution->decision->dossier;

        return $this->pdf(
            'pdf.mandat',
            compact('execution', 'dossier'),
            'mandat_depot_' . str_replace('/', '_', $dossier->numero_rp ?? 'inconnu') . '.pdf'
        );
    }

    public function jugement(Decision $decision)
    {
        abort_unless($decision->type_decision === 'jugement', 403, 'Cette décision n\'est pas un jugement.');

        $decision->load(['dossier.registre', 'dossier.parties', 'audience']);
        $dossier = $decision->dossier;

        return $this->pdf(
            'pdf.jugement',
            compact('decision', 'dossier'),
            'jugement_' . str_replace('/', '_', $dossier->numero_rp ?? 'inconnu') . '.pdf'
        );
    }

    public function ordonnance(Decision $decision)
    {
        abort_unless($decision->type_decision === 'ordonnance', 403, 'Cette décision n\'est pas une ordonnance.');

        $decision->load(['dossier.registre', 'dossier.parties', 'audience']);
        $dossier = $decision->dossier;

        return $this->pdf(
            'pdf.ordonnance',
            compact('decision', 'dossier'),
            'ordonnance_' . str_replace('/', '_', $dossier->numero_rp ?? 'inconnu') . '.pdf'
        );
    }

    public function notification(Decision $decision)
    {
        $decision->load(['dossier.registre', 'dossier.parties', 'audience']);
        $dossier = $decision->dossier;

        return $this->pdf(
            'pdf.notification_decision',
            compact('decision', 'dossier'),
            'notification_decision_' . str_replace('/', '_', $dossier->numero_rp ?? 'inconnu') . '.pdf'
        );
    }
}
