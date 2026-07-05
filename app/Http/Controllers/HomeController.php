<?php

namespace App\Http\Controllers;

use App\Models\ActeInstruction;
use App\Models\Audience;
use App\Models\Decision;
use App\Models\Dossier;
use App\Models\Execution;
use App\Models\Institution_executante;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function accueil_admin()
    {
        $stats = $this->statsGlobales();
        $derniersDossiers = Dossier::with('registre')->latest()->limit(5)->get();
        return view('welcome', compact('stats', 'derniersDossiers'));
    }

    public function accueil_greffier()
    {
        $user  = Auth::user();
        $stats = [
            'mes_dossiers'   => Dossier::where('id_greffier', $user->id)->count(),
            'en_cours'       => Dossier::where('id_greffier', $user->id)->where('statut', 'En cours')->count(),
            // 'audiences'      => Audience::whereHas('dossiers', fn($q) => $q->where('id_greffier', $user->id))->count(),
            // 'executions'     => Execution::where('statut_execution', 'en_cours')->count(),
            // 'archives'       => Dossier::where('statut', 'Archivé')->count(),
        ];
        $derniersDossiers = Dossier::with('registre')
            ->where('id_greffier', $user->id)
            ->latest()->limit(5)->get();

        return view('gref_accueil', compact('stats', 'derniersDossiers'));
    }

    public function accueil_procureur()
    {
        $stats = [
            'total'     => Dossier::count(),
            'audiences' => Audience::count(),
            'decisions' => Decision::count(),
            'archives'  => Dossier::where('statut', 'Archivé')->count(),
            'en_cours'  => Dossier::where('statut', 'En cours')->count(),
            'orientes'  => Dossier::whereNotNull('decision_orientation')->count(),
            'juges'     => Dossier::where('statut', 'Jugé')->count(),
            'executes'  => Dossier::where('statut', 'Exécuté')->count(),
        ];
        $derniersDossiers = Dossier::with('registre')->latest()->limit(5)->get();

        return view('procureur.accueil', compact('stats', 'derniersDossiers'));
    }

    public function accueil_juge()
    {
        $stats = [
            'a_juger'   => Dossier::whereIn('statut', ['Orienté', 'En instruction'])->count(),
            'audiences' => Audience::count(),
            'jugements' => Decision::count(),
            'archives'  => Dossier::where('statut', 'Archivé')->count(),
        ];
        $prochaines = Audience::with('dossiers.registre')
            ->where('date_audience', '>=', now()->toDateString())
            ->orderBy('date_audience')
            ->limit(5)
            ->get();

        return view('juge.accueil', compact('stats', 'prochaines'));
    }

    public function accueil_substitut()
    {
        $user  = Auth::user();
        $stats = [
            'total'     => Dossier::count(),
            'en_cours'  => Dossier::where('statut', 'En cours')->count(),
            'orientes'  => Dossier::whereNotNull('decision_orientation')->count(),
            'audiences' => Audience::count(),
        ];
        $derniersDossiers = Dossier::with('registre')->latest()->limit(5)->get();

        return view('procureur.accueil', compact('stats', 'derniersDossiers'));
    }

    public function accueil_pj()
    {
        $actes = ActeInstruction::with(['instruction.dossier.registre', 'demandeur'])
            ->whereIn('statut', ['en_attente', 'en_cours'])
            ->latest()
            ->limit(10)
            ->get();

        $stats = [
            'en_attente' => ActeInstruction::where('statut', 'en_attente')->count(),
            'en_cours'   => ActeInstruction::where('statut', 'en_cours')->count(),
            'executes'   => ActeInstruction::where('statut', 'execute')->count(),
        ];

        return view('externe.pj.accueil', compact('actes', 'stats'));
    }

    public function accueil_huissier()
    {
        $audiences = Audience::with(['dossiers.registre'])
            ->where('date_audience', '>=', now()->subDays(30)->toDateString())
            ->orderBy('date_audience')
            ->paginate(10);

        $stats = [
            'a_signifier' => Audience::where('date_audience', '>=', now()->toDateString())->count(),
            'passees'     => Audience::where('date_audience', '<', now()->toDateString())->count(),
        ];

        return view('externe.huissier.accueil', compact('audiences', 'stats'));
    }

    public function accueil_penitentiaire()
    {
        $institution = Institution_executante::where('type_institution', 'penitentiaire')->first();

        $executions = Execution::with(['decision.dossier.registre'])
            ->where('type_peine', 'privative_liberte')
            ->when($institution, fn($q) => $q->where('id_institution', $institution->id_institution))
            ->latest()
            ->paginate(10);

        $stats = [
            'en_cours'     => $executions->where('statut_execution', 'en_cours')->count(),
            'executees'    => Execution::where('type_peine', 'privative_liberte')->where('statut_execution', 'executee')->count(),
            'non_executees'=> Execution::where('type_peine', 'privative_liberte')->where('statut_execution', 'non_executee')->count(),
        ];

        return view('externe.penitentiaire.accueil', compact('executions', 'stats'));
    }

    public function accueil_tresor()
    {
        $institution = Institution_executante::where('type_institution', 'tresor')->first();

        $executions = Execution::with(['decision.dossier.registre'])
            ->where('type_peine', 'pecuniaire')
            ->when($institution, fn($q) => $q->where('id_institution', $institution->id_institution))
            ->latest()
            ->paginate(10);

        $stats = [
            'en_cours'      => $executions->where('statut_execution', 'en_cours')->count(),
            'recouvrees'    => Execution::where('type_peine', 'pecuniaire')->where('statut_execution', 'executee')->count(),
            'non_recouvrees'=> Execution::where('type_peine', 'pecuniaire')->where('statut_execution', 'non_executee')->count(),
        ];

        return view('externe.tresor.accueil', compact('executions', 'stats'));
    }

    private function statsGlobales(): array
    {
        $parStatut = Dossier::select('statut', DB::raw('COUNT(*) as total'))
            ->groupBy('statut')->pluck('total', 'statut');

        return [
            'total'          => Dossier::count(),
            'en_cours'       => $parStatut['En cours']       ?? 0,
            'oriente'        => $parStatut['Orienté']        ?? 0,
            'juge'           => $parStatut['Jugé']           ?? 0,
            'execute'        => $parStatut['Exécuté']        ?? 0,
            'archive'        => $parStatut['Archivé']        ?? 0,
            'audiences'      => Audience::count(),
            'decisions'      => Decision::count(),
            'utilisateurs'   => User::count(),
        ];
    }
}
