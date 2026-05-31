<?php

namespace App\Http\Controllers;

use App\Models\Audience;
use App\Models\Decision;
use App\Models\Dossier;
use App\Models\Execution;
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
            'audiences'      => Audience::whereHas('dossiers', fn($q) => $q->where('id_greffier', $user->id))->count(),
            'executions'     => Execution::where('statut_execution', 'en_cours')->count(),
            'archives'       => Dossier::where('statut', 'Archivé')->count(),
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
