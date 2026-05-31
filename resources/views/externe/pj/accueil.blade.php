@extends('externe.layout.app')
@php $roleLabel = 'Police Judiciaire'; $homeRoute = route('accueil.pj'); @endphp

@section('sidebar_links')
<li class="nav-item active">
    <a class="nav-link" href="{{ route('accueil.pj') }}">
        <i class="fas fa-fw fa-tasks"></i>
        <span>Mes actes à exécuter</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('pj.depot.form') }}">
        <i class="fas fa-fw fa-upload"></i>
        <span>Dépôt de pièces</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('messagerie.index') }}">
        <i class="fas fa-fw fa-envelope"></i>
        <span>Messagerie</span>
    </a>
</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <h4 class="fw-bold text-dark mb-4">Espace Police Judiciaire</h4>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        @foreach([
            ['En attente', $stats['en_attente'], 'warning', 'fa-hourglass-half'],
            ['En cours',   $stats['en_cours'],   'primary', 'fa-spinner'],
            ['Exécutés',   $stats['executes'],   'success', 'fa-check-circle'],
        ] as [$label, $val, $color, $icon])
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">{{ $label }}</div>
                        <div class="fs-3 fw-bold text-{{ $color }}">{{ $val }}</div>
                    </div>
                    <i class="fas {{ $icon }} fa-2x text-{{ $color }} opacity-50"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Actes à exécuter --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-semibold">Actes d'instruction à exécuter</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Dossier</th>
                        <th>Type d'acte</th>
                        <th>Objet</th>
                        <th>Demandé le</th>
                        <th>Échéance</th>
                        <th>Statut</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $acteLabels = [
                        'commission_rogatoire' => 'Commission rogatoire',
                        'expertise'            => 'Expertise',
                        'audition'             => 'Audition',
                        'confrontation'        => 'Confrontation',
                        'perquisition'         => 'Perquisition',
                        'autre'                => 'Autre',
                    ];
                    @endphp
                    @forelse($actes as $acte)
                    @php
                    $retard = $acte->isEnRetard();
                    $sc = ['en_attente'=>'secondary','en_cours'=>'warning'][$acte->statut] ?? 'secondary';
                    @endphp
                    <tr class="{{ $retard ? 'table-danger' : '' }}">
                        <td>
                            <div class="fw-semibold small">
                                {{ $acte->instruction->dossier->numero_registre ?? '—' }}
                            </div>
                        </td>
                        <td class="small">{{ $acteLabels[$acte->type_acte] ?? $acte->type_acte }}</td>
                        <td class="small">{{ Str::limit($acte->objet, 50) }}</td>
                        <td class="small">{{ $acte->date_demande->format('d/m/Y') }}</td>
                        <td class="small">
                            @if($acte->date_echeance)
                                <span class="{{ $retard ? 'text-danger fw-bold' : '' }}">
                                    {{ $acte->date_echeance->format('d/m/Y') }}
                                    @if($retard) <i class="fas fa-exclamation-triangle"></i> @endif
                                </span>
                            @else —
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $sc }} {{ $sc==='warning'?'text-dark':'' }}">
                                {{ ucfirst(str_replace('_',' ',$acte->statut)) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('actes.statut', $acte) }}">
                                @csrf @method('PATCH')
                                <div class="d-flex gap-1 justify-content-center">
                                    <select name="statut" class="form-select form-select-sm" style="width:110px">
                                        <option value="en_cours"   {{ $acte->statut==='en_cours'?'selected':'' }}>En cours</option>
                                        <option value="execute">Exécuté</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-check-circle fa-3x mb-3 opacity-25 d-block"></i>
                            Aucun acte en attente
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
