@extends('admin.layout.app')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Tableau de bord statistique</h4>
        <small class="text-muted">Données au {{ now()->format('d/m/Y') }}</small>
    </div>

    {{-- KPI principaux --}}
    <div class="row g-3 mb-4">
        @php
        $kpis = [
            ['label' => 'Total dossiers',   'value' => $stats['total'],   'color' => 'primary',   'icon' => 'fa-folder-open'],
            ['label' => 'En cours',         'value' => $stats['en_cours'], 'color' => 'warning',   'icon' => 'fa-hourglass-half'],
            ['label' => 'Jugés',            'value' => $stats['juge'],    'color' => 'info',      'icon' => 'fa-gavel'],
            ['label' => 'Exécutés',         'value' => $stats['execute'], 'color' => 'success',   'icon' => 'fa-check-circle'],
            ['label' => 'Classés',          'value' => $stats['classe'],  'color' => 'secondary', 'icon' => 'fa-ban'],
            ['label' => 'Archivés',         'value' => $stats['archive'], 'color' => 'dark',      'icon' => 'fa-archive'],
            ['label' => 'Audiences',        'value' => $stats['audiences'],'color' => 'primary',  'icon' => 'fa-calendar-check'],
            ['label' => 'Décisions',        'value' => $stats['decisions'],'color' => 'success',  'icon' => 'fa-file-signature'],
        ];
        @endphp

        @foreach($kpis as $kpi)
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">{{ $kpi['label'] }}</div>
                        <div class="fs-3 fw-bold text-{{ $kpi['color'] }}">{{ $kpi['value'] }}</div>
                    </div>
                    <i class="fas {{ $kpi['icon'] }} fa-2x text-{{ $kpi['color'] }} opacity-50"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-4 mb-4">
        {{-- Graphe : dossiers par mois --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-semibold">Enregistrements — 6 derniers mois</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartMois" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- Répartition par statut --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-semibold">Répartition par statut</h6>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <canvas id="chartStatut" style="max-height:220px"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        {{-- Par registre --}}
        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-semibold">Dossiers par registre</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr><th>Registre</th><th class="text-end">Dossiers</th><th>Poids</th></tr>
                        </thead>
                        <tbody>
                            @foreach($parRegistre as $reg)
                            @php $pct = $stats['total'] > 0 ? round($reg->dossiers_count / $stats['total'] * 100) : 0; @endphp
                            <tr>
                                <td class="fw-semibold">{{ $reg->nom }}</td>
                                <td class="text-end">{{ $reg->dossiers_count }}</td>
                                <td style="width:40%">
                                    <div class="progress" style="height:6px">
                                        <div class="progress-bar bg-primary" style="width:{{ $pct }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ $pct }}%</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Orientations --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-semibold">Décisions d'orientation</h6>
                </div>
                <div class="card-body">
                    @php $totalOrient = $orientations->sum(); @endphp
                    @foreach($orientationLabels as $key => $label)
                    @php $n = $orientations[$key] ?? 0; $pct = $totalOrient > 0 ? round($n / $totalOrient * 100) : 0; @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>{{ $label }}</span>
                            <span class="fw-semibold">{{ $n }} <span class="text-muted">({{ $pct }}%)</span></span>
                        </div>
                        <div class="progress" style="height:6px">
                            <div class="progress-bar
                                @if($key === 'classement_sans_suite') bg-danger
                                @elseif($key === 'citation_directe') bg-primary
                                @elseif($key === 'comparution_immediate') bg-warning
                                @else bg-dark @endif"
                                style="width:{{ $pct }}%"></div>
                        </div>
                    </div>
                    @endforeach
                    @if($totalOrient === 0)
                        <div class="text-muted text-center py-3 small">Aucune orientation saisie</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Exécutions --}}
        <div class="col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-semibold">Exécutions</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartExec" style="max-height:160px"></canvas>
                    <div class="mt-3 small">
                        <div class="d-flex justify-content-between mb-1">
                            <span><span class="badge bg-success me-1">&nbsp;</span> Exécutées</span>
                            <strong>{{ $stats['executions_ok'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><span class="badge bg-warning text-dark me-1">&nbsp;</span> En cours</span>
                            <strong>{{ $stats['executions_en_cours'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Derniers dossiers --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">Derniers dossiers enregistrés</h6>
            <a href="{{ route('dossiers.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Dossier</th><th>Registre</th><th>Statut</th><th>Date</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($derniersDossiers as $d)
                    @php
                    $sc = ['En cours'=>'warning','Orienté'=>'info','En instruction'=>'primary','Jugé'=>'success','Exécuté'=>'success','Classé'=>'secondary','Archivé'=>'dark'][$d->statut] ?? 'secondary';
                    @endphp
                    <tr>
                        <td><a href="{{ route('dossiers.show', $d) }}" class="fw-semibold text-primary">{{ $d->numero_registre }}</a></td>
                        <td><span class="badge bg-light border text-dark">{{ $d->registre->nom ?? '-' }}</span></td>
                        <td><span class="badge bg-{{ $sc }} {{ $sc==='warning'?'text-dark':'' }}">{{ $d->statut }}</span></td>
                        <td class="small text-muted">{{ \Carbon\Carbon::parse($d->created_at)->format('d/m/Y') }}</td>
                        <td><a href="{{ route('dossiers.show', $d) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
const moisLabels = @json($moisLabels);
const moisData   = @json($moisData);

// Bar chart — dossiers par mois
new Chart(document.getElementById('chartMois'), {
    type: 'bar',
    data: {
        labels: moisLabels,
        datasets: [{
            label: 'Dossiers enregistrés',
            data: moisData,
            backgroundColor: 'rgba(78,115,223,0.7)',
            borderColor: 'rgba(78,115,223,1)',
            borderWidth: 1,
            borderRadius: 4,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});

// Doughnut — répartition statuts
new Chart(document.getElementById('chartStatut'), {
    type: 'doughnut',
    data: {
        labels: ['En cours', 'Orienté', 'Jugé', 'Exécuté', 'Classé', 'Archivé'],
        datasets: [{
            data: [
                {{ $stats['en_cours'] }},
                {{ $stats['oriente'] }},
                {{ $stats['juge'] }},
                {{ $stats['execute'] }},
                {{ $stats['classe'] }},
                {{ $stats['archive'] }},
            ],
            backgroundColor: ['#f6c23e','#36b9cc','#1cc88a','#28a745','#858796','#343a40'],
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } } }
});

// Doughnut — exécutions
new Chart(document.getElementById('chartExec'), {
    type: 'doughnut',
    data: {
        labels: ['Exécutées', 'En cours'],
        datasets: [{
            data: [{{ $stats['executions_ok'] }}, {{ $stats['executions_en_cours'] }}],
            backgroundColor: ['#1cc88a', '#f6c23e'],
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});
</script>
@endsection
