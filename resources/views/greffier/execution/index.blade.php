@extends('greffier.layout.app')

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">En cours</div>
                        <div class="fs-4 fw-bold text-warning">{{ $stats['en_cours'] }}</div>
                    </div>
                    <i class="fas fa-hourglass-half fa-2x text-warning opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Exécutées</div>
                        <div class="fs-4 fw-bold text-success">{{ $stats['executee'] }}</div>
                    </div>
                    <i class="fas fa-check-circle fa-2x text-success opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Non exécutées</div>
                        <div class="fs-4 fw-bold text-danger">{{ $stats['non_executee'] }}</div>
                    </div>
                    <i class="fas fa-times-circle fa-2x text-danger opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-dark mb-0">Exécutions des décisions</h4>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Dossier</th>
                        <th>Type de peine</th>
                        <th>Institution</th>
                        <th>Date d'exécution</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($executions as $execution)
                    @php
                        $statutColors = [
                            'en_cours'     => 'warning',
                            'executee'     => 'success',
                            'non_executee' => 'danger',
                        ];
                        $statutLabels = [
                            'en_cours'     => 'En cours',
                            'executee'     => 'Exécutée',
                            'non_executee' => 'Non exécutée',
                        ];
                        $peineLabels = [
                            'privative_liberte' => 'Privative de liberté',
                            'pecuniaire'        => 'Pécuniaire',
                            'complementaire'    => 'Complémentaire',
                        ];
                        $sc = $statutColors[$execution->statut_execution] ?? 'secondary';
                        $dossier = $execution->decision->dossier ?? null;
                    @endphp
                    <tr>
                        <td>
                            @if($dossier)
                            <a href="{{ route('dossiers.show', $dossier) }}" class="fw-semibold text-primary">
                                {{ $dossier->numero_registre }}
                            </a>
                            <div class="small text-muted">{{ $dossier->registre->nom ?? '-' }}</div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $peineLabels[$execution->type_peine] ?? $execution->type_peine }}</td>
                        <td>{{ $execution->institution->nom ?? '-' }}</td>
                        <td>
                            {{ $execution->date_execution
                                ? \Carbon\Carbon::parse($execution->date_execution)->format('d/m/Y')
                                : '—' }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $sc }} {{ $sc === 'warning' ? 'text-dark' : '' }}">
                                {{ $statutLabels[$execution->statut_execution] ?? $execution->statut_execution }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('executions.show', $execution) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-clipboard-check fa-3x mb-3 opacity-25 d-block"></i>
                            Aucune exécution enregistrée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($executions, 'links'))
        <div class="card-footer bg-white">{{ $executions->links() }}</div>
        @endif
    </div>
</div>
@endsection
