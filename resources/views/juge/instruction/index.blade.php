@extends('juge.layout.app')

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($alertes > 0)
    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>{{ $alertes }} détention(s) provisoire(s) arrivent à échéance dans moins de 7 jours.</strong>
        Consultez les instructions concernées.
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Mes instructions judiciaires</h4>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Dossier</th>
                        <th>Procureur</th>
                        <th>Date saisine</th>
                        <th>Actes</th>
                        <th>Détention</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($instructions as $instruction)
                    @php
                    $statutColors = ['en_cours'=>'primary','clos_renvoi'=>'success','clos_non_lieu'=>'secondary'];
                    $statutLabels = ['en_cours'=>'En cours','clos_renvoi'=>'Clos — Renvoi','clos_non_lieu'=>'Clos — Non-lieu'];
                    $sc = $statutColors[$instruction->statut] ?? 'secondary';
                    $actesEnCours = $instruction->actes->whereNotIn('statut', ['execute','annule'])->count();
                    $detentionActive = $instruction->detentions->where('statut','!=','levee')->first();
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('dossiers.show', $instruction->dossier) }}"
                               class="fw-semibold text-primary">
                                {{ $instruction->dossier->numero_registre }}
                            </a>
                            <div class="small text-muted">{{ $instruction->dossier->registre->nom ?? '-' }}</div>
                        </td>
                        <td class="small">{{ $instruction->procureur->name ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($instruction->date_saisine)->format('d/m/Y') }}</td>
                        <td>
                            @if($actesEnCours > 0)
                                <span class="badge bg-warning text-dark">{{ $actesEnCours }} en cours</span>
                            @else
                                <span class="badge bg-light border text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($detentionActive)
                                @php $alerte = $detentionActive->alerteNiveau(); @endphp
                                <span class="badge bg-{{ $alerte }}
                                    {{ $alerte === 'warning' ? 'text-dark' : '' }}">
                                    {{ $detentionActive->joursRestants() >= 0
                                        ? $detentionActive->joursRestants() . ' j'
                                        : 'DÉPASSÉ' }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $sc }}">
                                {{ $statutLabels[$instruction->statut] ?? $instruction->statut }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('instructions.show', $instruction) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-balance-scale fa-3x mb-3 opacity-25 d-block"></i>
                            Aucune instruction assignée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($instructions, 'links'))
        <div class="card-footer bg-white">{{ $instructions->links() }}</div>
        @endif
    </div>
</div>
@endsection
