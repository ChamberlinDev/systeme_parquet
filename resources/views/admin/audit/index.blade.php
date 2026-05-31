@extends('admin.layout.app')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Journal d'audit</h4>
        <a href="{{ route('audit.export', request()->query()) }}"
           class="btn btn-outline-success btn-sm">
            <i class="fas fa-file-csv"></i> Exporter CSV
        </a>
    </div>

    {{-- Filtres --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('audit.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small fw-semibold mb-1">Recherche</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" name="q" class="form-control"
                                value="{{ $filtres['q'] ?? '' }}"
                                placeholder="IP, détail, type d'objet…">
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-semibold mb-1">Action</label>
                        <select name="action" class="form-select form-select-sm">
                            <option value="">Toutes</option>
                            @foreach($actions as $code => $label)
                            <option value="{{ $code }}"
                                {{ ($filtres['action'] ?? '') === $code ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-semibold mb-1">Utilisateur</label>
                        <select name="user_id" class="form-select form-select-sm">
                            <option value="">Tous</option>
                            @foreach($users as $u)
                            <option value="{{ $u->id }}"
                                {{ ($filtres['user_id'] ?? '') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-semibold mb-1">Du</label>
                        <input type="date" name="date_du" class="form-control form-control-sm"
                            value="{{ $filtres['date_du'] ?? '' }}">
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-semibold mb-1">Au</label>
                        <input type="date" name="date_au" class="form-control form-control-sm"
                            value="{{ $filtres['date_au'] ?? '' }}">
                    </div>

                    <div class="col-lg-1">
                        <div class="d-flex gap-1">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-filter"></i>
                            </button>
                            @if(collect($filtres)->filter()->isNotEmpty())
                            <a href="{{ route('audit.index') }}"
                               class="btn btn-outline-secondary btn-sm" title="Réinitialiser">
                                <i class="fas fa-times"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">
                {{ $logs->total() }} entrée(s)
            </h6>
            <small class="text-muted">30 par page</small>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:0.85rem">
                <thead class="table-light">
                    <tr>
                        <th style="width:150px">Date / Heure</th>
                        <th>Utilisateur</th>
                        <th>Action</th>
                        <th>Objet</th>
                        <th>Détail</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    @php
                    $c = $couleurs[$log->action] ?? 'secondary';
                    @endphp
                    <tr>
                        <td class="text-muted small">
                            {{ $log->created_at->format('d/m/Y') }}<br>
                            <span class="fw-semibold text-dark">{{ $log->created_at->format('H:i:s') }}</span>
                        </td>
                        <td>
                            @if($log->user)
                            <div class="fw-semibold">{{ $log->user->name }}</div>
                            <small class="text-muted">{{ $log->user->email }}</small>
                            @else
                            <span class="text-muted">Système</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $c }} {{ $c === 'warning' || $c === 'light' ? 'text-dark' : '' }}">
                                {{ AuditLog::$labels[$log->action] ?? $log->action }}
                            </span>
                        </td>
                        <td class="small text-muted">
                            @if($log->objet_type)
                            {{ $log->objet_type }}
                            @if($log->objet_id)
                                <span class="badge bg-light border text-dark">#{{ $log->objet_id }}</span>
                            @endif
                            @else —
                            @endif
                        </td>
                        <td class="small">{{ Str::limit($log->detail ?? '—', 80) }}</td>
                        <td class="small text-muted font-monospace">{{ $log->ip_address ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-clipboard-list fa-3x mb-3 opacity-25 d-block"></i>
                            Aucune entrée dans le journal
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($logs, 'links'))
        <div class="card-footer bg-white">{{ $logs->links() }}</div>
        @endif
    </div>
</div>
@endsection
