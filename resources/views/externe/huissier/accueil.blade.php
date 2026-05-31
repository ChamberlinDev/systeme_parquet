@extends('externe.layout.app')
@php $roleLabel = 'Huissier de justice'; $homeRoute = route('accueil.huissier'); @endphp

@section('sidebar_links')
<li class="nav-item">
    <a class="nav-link" href="{{ route('accueil.huissier') }}">
        <i class="fas fa-fw fa-calendar-check"></i>
        <span>Convocations à signifier</span>
    </a>
</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <h4 class="fw-bold text-dark mb-4">Espace Huissier de Justice</h4>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">À signifier</div>
                        <div class="fs-3 fw-bold text-warning">{{ $stats['a_signifier'] }}</div>
                    </div>
                    <i class="fas fa-file-signature fa-2x text-warning opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Passées (30 jours)</div>
                        <div class="fs-3 fw-bold text-secondary">{{ $stats['passees'] }}</div>
                    </div>
                    <i class="fas fa-history fa-2x text-secondary opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-semibold">Audiences — Convocations à exécuter</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date audience</th>
                        <th>Type</th>
                        <th>Salle</th>
                        <th>Dossiers</th>
                        <th>Parties à convoquer</th>
                        <th>PV signification</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($audiences as $audience)
                    @php
                    $typeColors=['correctionnelle'=>'warning','criminelle'=>'danger','sociale'=>'info','refere'=>'secondary'];
                    $tc=$typeColors[$audience->type_audience]??'secondary';
                    $isPast = \Carbon\Carbon::parse($audience->date_audience)->isPast();
                    @endphp
                    <tr class="{{ $isPast ? 'text-muted' : '' }}">
                        <td>
                            <div class="fw-semibold">{{ \Carbon\Carbon::parse($audience->date_audience)->format('d/m/Y') }}</div>
                            <small class="{{ $isPast ? 'text-muted' : 'text-success' }}">
                                {{ $isPast ? 'Passée' : \Carbon\Carbon::parse($audience->date_audience)->diffForHumans() }}
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $tc }} {{ $tc==='warning'?'text-dark':'' }}">
                                {{ ucfirst($audience->type_audience) }}
                            </span>
                        </td>
                        <td>{{ $audience->salle }}</td>
                        <td><span class="badge bg-light border text-dark">{{ $audience->dossiers->count() }}</span></td>
                        <td>
                            @php $parties = $audience->dossiers->flatMap(fn($d) => $d->parties); @endphp
                            <span class="small">{{ $parties->count() }} partie(s)</span>
                        </td>
                        <td>
                            <a href="{{ route('audiences.show', $audience) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-calendar-alt fa-3x mb-3 opacity-25 d-block"></i>
                            Aucune audience sur les 30 derniers jours
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($audiences, 'links'))
        <div class="card-footer bg-white">{{ $audiences->links() }}</div>
        @endif
    </div>
</div>
@endsection
