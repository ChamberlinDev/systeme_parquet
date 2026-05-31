@extends('juge.layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Dossiers à juger</div>
                        <div class="fs-3 fw-bold text-primary">{{ $stats['a_juger'] }}</div>
                    </div>
                    <i class="fas fa-balance-scale fa-2x text-primary opacity-50"></i>
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
                    <i class="fas fa-gavel fa-2x text-success opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Jugements</div>
                        <div class="fs-3 fw-bold text-info">{{ $stats['jugements'] }}</div>
                    </div>
                    <i class="fas fa-file-signature fa-2x text-info opacity-50"></i>
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

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">Prochaines audiences</h6>
            <a href="{{ route('audiences.index.juge') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Date</th><th>Salle</th><th>Type</th><th>Dossiers</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($prochaines as $audience)
                    @php $typeColors=['correctionnelle'=>'warning','criminelle'=>'danger','sociale'=>'info','refere'=>'secondary']; $tc=$typeColors[$audience->type_audience]??'secondary'; @endphp
                    <tr>
                        <td class="fw-semibold">{{ \Carbon\Carbon::parse($audience->date_audience)->format('d/m/Y') }}</td>
                        <td>{{ $audience->salle }}</td>
                        <td><span class="badge bg-{{ $tc }} {{ $tc==='warning'?'text-dark':'' }}">{{ ucfirst($audience->type_audience) }}</span></td>
                        <td><span class="badge bg-light border text-dark">{{ $audience->dossiers->count() }}</span></td>
                        <td><a href="{{ route('audiences.show', $audience) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">Aucune audience à venir</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
