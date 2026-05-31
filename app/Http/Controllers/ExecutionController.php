<?php

namespace App\Http\Controllers;

use App\Mail\ExecutionCreeMail;
use App\Services\AuditService;
use App\Models\Decision;
use App\Models\Dossier;
use App\Models\DossierHistorique;
use App\Models\Execution;
use App\Models\Institution_executante;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExecutionController extends Controller
{
    public function index()
    {
        $executions = Execution::with(['decision.dossier.registre', 'institution'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $stats = [
            'en_cours'    => Execution::where('statut_execution', 'en_cours')->count(),
            'executee'    => Execution::where('statut_execution', 'executee')->count(),
            'non_executee' => Execution::where('statut_execution', 'non_executee')->count(),
        ];

        return view('greffier.execution.index', compact('executions', 'stats'));
    }

    public function create(Decision $decision)
    {
        $decision->load('dossier.registre');
        $institutions = Institution_executante::orderBy('type_institution')->get();

        return view('greffier.execution.ajout', compact('decision', 'institutions'));
    }

    public function store(Request $request, Decision $decision)
    {
        $request->validate([
            'id_institution'  => 'required|exists:institution_executantes,id_institution',
            'type_peine'      => 'required|in:privative_liberte,pecuniaire,complementaire',
            'date_execution'  => 'nullable|date',
        ]);

        $execution = Execution::create([
            'id_decision'     => $decision->id_decision,
            'id_institution'  => $request->id_institution,
            'type_peine'      => $request->type_peine,
            'date_execution'  => $request->date_execution,
            'statut_execution' => 'en_cours',
        ]);

        $peineLabels = [
            'privative_liberte' => 'Privative de liberté',
            'pecuniaire'        => 'Pécuniaire',
            'complementaire'    => 'Complémentaire',
        ];

        $institution = Institution_executante::find($request->id_institution);

        DossierHistorique::create([
            'id_dossier' => $decision->id_dossier,
            'user_id'    => Auth::id(),
            'action'     => 'Exécution créée',
            'detail'     => $peineLabels[$request->type_peine] . ' — ' . ($institution->nom ?? ''),
        ]);

        AuditService::log('CREATION_EXECUTION', 'Execution', $execution->id_execution,
            $peineLabels[$request->type_peine] . ' — ' . ($institution->nom ?? ''));

        // Notifier l'institution si elle a un email configuré
        $execution->load('decision.dossier', 'institution');
        if ($institution && $institution->email) {
            NotificationService::sendMail($institution->email, new ExecutionCreeMail($execution));
        }

        return redirect()->route('executions.show', $execution)
            ->with('success', 'Exécution enregistrée.');
    }

    public function show(Execution $execution)
    {
        $execution->load(['decision.dossier.registre', 'decision.audience', 'institution']);

        return view('greffier.execution.show', compact('execution'));
    }

    public function updateStatut(Request $request, Execution $execution)
    {
        $request->validate([
            'statut_execution' => 'required|in:en_cours,executee,non_executee',
            'date_execution'   => 'nullable|date',
        ]);

        $execution->update([
            'statut_execution' => $request->statut_execution,
            'date_execution'   => $request->date_execution ?? $execution->date_execution,
        ]);

        $statutLabels = [
            'en_cours'     => 'En cours',
            'executee'     => 'Exécutée',
            'non_executee' => 'Non exécutée',
        ];

        AuditService::log('MAJ_EXECUTION', 'Execution', $execution->id_execution,
            $statutLabels[$request->statut_execution] ?? $request->statut_execution);

        $dossier = $execution->decision->dossier;

        DossierHistorique::create([
            'id_dossier' => $dossier->id_dossier,
            'user_id'    => Auth::id(),
            'action'     => 'Statut exécution mis à jour',
            'detail'     => $statutLabels[$request->statut_execution],
        ]);

        // Si toutes les exécutions du dossier sont terminées → statut "Exécuté"
        $toutesExecutees = $dossier->decisions()
            ->with('executions')
            ->get()
            ->flatMap(fn($d) => $d->executions)
            ->every(fn($e) => $e->statut_execution === 'executee');

        if ($toutesExecutees && $dossier->decisions->isNotEmpty()) {
            $dossier->update(['statut' => 'Exécuté']);

            DossierHistorique::create([
                'id_dossier' => $dossier->id_dossier,
                'user_id'    => Auth::id(),
                'action'     => 'Dossier clôturé',
                'detail'     => 'Toutes les exécutions sont terminées.',
            ]);
        }

        return redirect()->route('executions.show', $execution)
            ->with('success', 'Statut mis à jour.');
    }
}
