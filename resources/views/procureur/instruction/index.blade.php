@extends('procureur.layout.app')

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Instructions judiciaires</h4>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Dossier</th>
                        <th>Juge d'instruction</th>
                        <th>Date saisine</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($instructions as $instruction)
                    @php
                    $statutColors = [
                        'en_cours'       => 'primary',
                        'clos_renvoi'    => 'success',
                        'clos_non_lieu'  => 'secondary',
                    ];
                    $statutLabels = [
                        'en_cours'       => 'En cours',
                        'clos_renvoi'    => 'Clos — Renvoi',
                        'clos_non_lieu'  => 'Clos — Non-lieu',
                    ];
                    $sc = $statutColors[$instruction->statut] ?? 'secondary';
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('dossiers.show', $instruction->dossier) }}"
                               class="fw-semibold text-primary">
                                {{ $instruction->dossier->numero_registre }}
                            </a>
                            <div class="small text-muted">
                                {{ $instruction->dossier->registre->nom ?? '-' }}
                            </div>
                        </td>
                        <td>{{ $instruction->juge->name ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($instruction->date_saisine)->format('d/m/Y') }}</td>
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
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-balance-scale fa-3x mb-3 opacity-25 d-block"></i>
                            Aucune instruction en cours
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
