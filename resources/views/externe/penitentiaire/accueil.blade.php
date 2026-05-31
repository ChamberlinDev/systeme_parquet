@extends('externe.layout.app')
@php $roleLabel = 'Administration Pénitentiaire'; $homeRoute = route('accueil.penitentiaire'); @endphp

@section('sidebar_links')
<li class="nav-item">
    <a class="nav-link" href="{{ route('accueil.penitentiaire') }}">
        <i class="fas fa-fw fa-lock"></i>
        <span>Mandats de détention</span>
    </a>
</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <h4 class="fw-bold text-dark mb-4">Espace Administration Pénitentiaire</h4>

    <div class="row g-3 mb-4">
        @foreach([
            ['En cours',      $stats['en_cours'],      'warning', 'fa-hourglass-half'],
            ['Exécutées',     $stats['executees'],     'success', 'fa-check-circle'],
            ['Non exécutées', $stats['non_executees'], 'danger',  'fa-times-circle'],
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

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-semibold">Mandats de détention — Peines privatives de liberté</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Dossier</th>
                        <th>Date d'exécution prévue</th>
                        <th>Statut</th>
                        <th class="text-center">Mise à jour</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($executions as $execution)
                    @php
                    $sc=['en_cours'=>'warning','executee'=>'success','non_executee'=>'danger'][$execution->statut_execution]??'secondary';
                    $labels=['en_cours'=>'En cours','executee'=>'Exécutée','non_executee'=>'Non exécutée'];
                    $dossier=$execution->decision->dossier??null;
                    @endphp
                    <tr>
                        <td>
                            @if($dossier)
                            <div class="fw-semibold">{{ $dossier->numero_registre }}</div>
                            <small class="text-muted">{{ $dossier->registre->nom??'-' }}</small>
                            @else —
                            @endif
                        </td>
                        <td>
                            {{ $execution->date_execution
                                ? \Carbon\Carbon::parse($execution->date_execution)->format('d/m/Y')
                                : '—' }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $sc }} {{ $sc==='warning'?'text-dark':'' }}">
                                {{ $labels[$execution->statut_execution]??$execution->statut_execution }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($execution->statut_execution !== 'executee')
                            <form method="POST" action="{{ route('executions.statut', $execution) }}"
                                  class="d-flex gap-2 justify-content-center">
                                @csrf @method('PATCH')
                                <input type="hidden" name="statut_execution" value="executee">
                                <input type="date" name="date_execution" class="form-control form-control-sm"
                                    style="width:140px" value="{{ date('Y-m-d') }}">
                                <button type="submit" class="btn btn-success btn-sm"
                                    onclick="return confirm('Confirmer l\'exécution ?')">
                                    <i class="fas fa-check"></i> Exécutée
                                </button>
                            </form>
                            @else
                            <span class="badge bg-success">Confirmée</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fas fa-lock fa-3x mb-3 opacity-25 d-block"></i>
                            Aucun mandat en attente
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
