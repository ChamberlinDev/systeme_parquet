@extends('greffier.layout.app')

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Audiences</h4>
        <a href="{{ route('audiences.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Planifier une audience
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Salle</th>
                        <th>Dossiers</th>
                        <th>PV</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($audiences as $audience)
                    @php
                    $typeColors = [
                        'correctionnelle' => 'warning',
                        'criminelle'      => 'danger',
                        'sociale'         => 'info',
                        'refere'          => 'secondary',
                    ];
                    $tc = $typeColors[$audience->type_audience] ?? 'secondary';
                    @endphp
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ \Carbon\Carbon::parse($audience->date_audience)->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($audience->date_audience)->diffForHumans() }}</small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $tc }} {{ $tc === 'warning' ? 'text-dark' : '' }}">
                                {{ ucfirst($audience->type_audience) }}
                            </span>
                        </td>
                        <td>{{ $audience->salle }}</td>
                        <td>
                            <span class="badge bg-light border text-dark">{{ $audience->dossiers->count() }} dossier(s)</span>
                        </td>
                        <td>
                            @if($audience->pv)
                                <span class="badge bg-success"><i class="fas fa-check"></i> Saisi</span>
                            @else
                                <span class="badge bg-light border text-muted">En attente</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('audiences.show', $audience) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-calendar-alt fa-3x mb-3 opacity-25 d-block"></i>
                            Aucune audience planifiée
                            <div class="mt-3">
                                <a href="{{ route('audiences.create') }}" class="btn btn-primary btn-sm">Planifier une audience</a>
                            </div>
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
