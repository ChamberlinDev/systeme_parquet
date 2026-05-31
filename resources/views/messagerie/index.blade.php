@php
    $user = auth()->user();
    // Détermination du layout selon le rôle
    if ($user?->hasRole('admin')) {
        $layout = 'admin.layout.app';
    } elseif ($user?->hasRole('greffier')) {
        $layout = 'greffier.layout.app';
    } elseif ($user?->hasRole('procureur') || $user?->hasRole('substitut')) {
        $layout = 'procureur.layout.app';
    } elseif ($user?->hasRole('juge')) {
        $layout = 'juge.layout.app';
    } else {
        $layout = 'externe.layout.app';
    }
    $roleLabel = 'Messagerie';
    $homeRoute = url()->current();
@endphp

@extends($layout)

@section('sidebar_links')
<li class="nav-item active">
    <a class="nav-link" href="{{ route('messagerie.index') }}">
        <i class="fas fa-fw fa-envelope"></i>
        <span>Messagerie</span>
    </a>
</li>
@endsection

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0">Messagerie inter-services</h4>
            <small class="text-muted">
                Votre service : <strong>{{ $services[$monService] ?? 'Non défini' }}</strong>
            </small>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#formMsg">
            <i class="fas fa-paper-plane"></i> Nouveau message
        </button>
    </div>

    {{-- Formulaire nouveau message --}}
    <div class="collapse mb-4" id="formMsg">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold">Envoyer un message</h6></div>
            <div class="card-body">
                <form method="POST" action="{{ route('messagerie.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Service destinataire <span class="text-danger">*</span></label>
                            <select name="service_destinataire" class="form-select form-select-sm" required>
                                <option value="">-- Choisir --</option>
                                @foreach($services as $code => $label)
                                    @if($code !== $monService)
                                    <option value="{{ $code }}">{{ $label }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Objet <span class="text-danger">*</span></label>
                            <input type="text" name="objet" class="form-control form-control-sm" required maxlength="150">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Dossier lié (facultatif)</label>
                            <select name="id_dossier" class="form-select form-select-sm">
                                <option value="">— Aucun —</option>
                                @foreach($dossiers as $d)
                                <option value="{{ $d->id_dossier }}">{{ $d->numero_registre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Message <span class="text-danger">*</span></label>
                            <textarea name="contenu" rows="3" class="form-control form-control-sm" required></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm mt-3">
                        <i class="fas fa-paper-plane"></i> Envoyer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Reçus --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold"><i class="fas fa-inbox text-primary"></i> Reçus</h6>
                    <span class="badge bg-light border text-dark">{{ $recus->total() }}</span>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($recus as $msg)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="fw-semibold small">{{ $msg->objet }}</span>
                            <span class="text-muted" style="font-size:0.7rem">{{ $msg->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="text-muted small mt-1">{{ $msg->contenu }}</div>
                        <div class="mt-1" style="font-size:0.72rem">
                            <span class="text-muted">De : {{ $msg->expediteur->name ?? '—' }}</span>
                            @if($msg->dossier)
                            <a href="{{ route('dossiers.show', $msg->dossier) }}" class="badge bg-light border text-dark text-decoration-none ms-1">
                                {{ $msg->dossier->numero_registre }}
                            </a>
                            @endif
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2 opacity-25 d-block"></i>
                        Aucun message reçu
                    </li>
                    @endforelse
                </ul>
                @if(method_exists($recus, 'links'))
                <div class="card-footer bg-white">{{ $recus->links() }}</div>
                @endif
            </div>
        </div>

        {{-- Envoyés --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold"><i class="fas fa-paper-plane text-success"></i> Envoyés</h6>
                    <span class="badge bg-light border text-dark">{{ $envoyes->total() }}</span>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($envoyes as $msg)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="fw-semibold small">{{ $msg->objet }}</span>
                            <span class="text-muted" style="font-size:0.7rem">{{ $msg->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="text-muted small mt-1">{{ $msg->contenu }}</div>
                        <div class="mt-1" style="font-size:0.72rem">
                            <span class="text-muted">Vers : {{ $services[$msg->service_destinataire] ?? $msg->service_destinataire }}</span>
                            @if($msg->lu)
                                <span class="badge bg-success ms-1">Lu</span>
                            @else
                                <span class="badge bg-secondary ms-1">Non lu</span>
                            @endif
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted py-4">
                        <i class="fas fa-paper-plane fa-2x mb-2 opacity-25 d-block"></i>
                        Aucun message envoyé
                    </li>
                    @endforelse
                </ul>
                @if(method_exists($envoyes, 'links'))
                <div class="card-footer bg-white">{{ $envoyes->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
