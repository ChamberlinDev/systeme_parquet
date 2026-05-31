@extends('procureur.layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Total Dossiers</div>
                        <div class="fs-3 fw-bold text-primary">{{ $stats['total'] }}</div>
                    </div>
                    <i class="fas fa-folder-open fa-2x text-primary opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Audiences</div>
                        <div class="fs-3 fw-bold text-success">{{ $stats['audiences'] }}</div>
                    </div>
                    <i class="fas fa-calendar-check fa-2x text-success opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Décisions</div>
                        <div class="fs-3 fw-bold text-info">{{ $stats['decisions'] }}</div>
                    </div>
                    <i class="fas fa-gavel fa-2x text-info opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Archivés</div>
                        <div class="fs-3 fw-bold text-secondary">{{ $stats['archives'] }}</div>
                    </div>
                    <i class="fas fa-archive fa-2x text-secondary opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">Derniers dossiers</h6>
                    <a href="{{ route('dossiers.index.procureur') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>Dossier</th><th>Registre</th><th>Statut</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            @forelse($derniersDossiers as $d)
                            @php $sc = ['En cours'=>'warning','Orienté'=>'info','Jugé'=>'success','Classé'=>'secondary','Archivé'=>'dark'][$d->statut] ?? 'secondary'; @endphp
                            <tr>
                                <td><a href="{{ route('dossiers.show', $d) }}" class="fw-semibold">{{ $d->numero_registre }}</a></td>
                                <td><span class="badge bg-light border text-dark">{{ $d->registre->nom ?? '-' }}</span></td>
                                <td><span class="badge bg-{{ $sc }} {{ $sc==='warning'?'text-dark':'' }}">{{ $d->statut }}</span></td>
                                <td><a href="{{ route('dossiers.show', $d) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">Aucun dossier</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white"><h6 class="mb-0 fw-semibold">Statistiques</h6></div>
                <div class="card-body">
                    @foreach([
                        ['En cours',  $stats['en_cours'],  'warning'],
                        ['Orientés',  $stats['orientes'],  'info'],
                        ['Jugés',     $stats['juges'],     'success'],
                        ['Exécutés',  $stats['executes'],  'primary'],
                        ['Archivés',  $stats['archives'],  'secondary'],
                    ] as [$label, $val, $color])
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small text-muted">{{ $label }}</span>
                        <span class="badge bg-{{ $color }} {{ $color==='warning'?'text-dark':'' }}">{{ $val }}</span>
                    </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small fw-semibold">Total</span>
                        <span class="fw-bold">{{ $stats['total'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
