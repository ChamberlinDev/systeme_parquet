<?php

namespace App\Http\Controllers;

use App\Models\Audience;
use App\Models\Decision;
use App\Models\Dossier;
use App\Models\Execution;
use App\Models\Registre;
use Illuminate\Support\Facades\DB;

class StatistiqueController extends Controller
{
    public function index()
    {
        // Compteurs par statut
        $parStatut = Dossier::select('statut', DB::raw('COUNT(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut');

        $stats = [
            'total'          => Dossier::count(),
            'en_cours'       => $parStatut['En cours']       ?? 0,
            'oriente'        => $parStatut['Orienté']        ?? 0,
            'en_instruction' => $parStatut['En instruction'] ?? 0,
            'classe'         => $parStatut['Classé']         ?? 0,
            'juge'           => $parStatut['Jugé']           ?? 0,
            'execute'        => $parStatut['Exécuté']        ?? 0,
            'archive'        => $parStatut['Archivé']        ?? 0,
            'audiences'      => Audience::count(),
            'decisions'      => Decision::count(),
            'executions_en_cours' => Execution::where('statut_execution', 'en_cours')->count(),
            'executions_ok'       => Execution::where('statut_execution', 'executee')->count(),
        ];

        // Dossiers par mois (6 derniers mois)
        $moisLabels = [];
        $moisData   = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $moisLabels[] = $date->translatedFormat('M Y');
            $moisData[]   = Dossier::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        // Répartition par registre
        $parRegistre = Registre::withCount('dossiers')
            ->orderByDesc('dossiers_count')
            ->get();

        // Répartition par type d'orientation
        $orientationLabels = [
            'classement_sans_suite'    => 'Classement sans suite',
            'citation_directe'         => 'Citation directe',
            'comparution_immediate'    => 'Comparution immédiate',
            'requisitoire_introductif' => 'Réquisitoire introductif',
        ];

        $orientations = Dossier::whereNotNull('decision_orientation')
            ->select('decision_orientation', DB::raw('COUNT(*) as total'))
            ->groupBy('decision_orientation')
            ->pluck('total', 'decision_orientation');

        // 10 derniers dossiers
        $derniersDossiers = Dossier::with('registre')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.statistiques.index', compact(
            'stats', 'moisLabels', 'moisData',
            'parRegistre', 'orientations', 'orientationLabels',
            'derniersDossiers'
        ));
    }
}
