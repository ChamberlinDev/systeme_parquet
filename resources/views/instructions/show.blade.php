@php
    $user = auth()->user();
    $layout   = 'admin.layout.app';
    $backRoute = 'dossiers.index';

    if ($user?->hasRole('juge')) {
        $layout   = 'juge.layout.app';
        $backRoute = 'instructions.index.juge';
    } elseif ($user?->hasRole('procureur')) {
        $layout   = 'procureur.layout.app';
        $backRoute = 'instructions.index.procureur';
    } elseif ($user?->hasRole('greffier')) {
        $layout   = 'greffier.layout.app';
        $backRoute = 'dossiers.index.greffier';
    }

    $statutColors = ['en_cours'=>'primary','clos_renvoi'=>'success','clos_non_lieu'=>'secondary'];
    $statutLabels = ['en_cours'=>'En cours','clos_renvoi'=>'Clos — Renvoi','clos_non_lieu'=>'Clos — Non-lieu'];
    $sc = $statutColors[$instruction->statut] ?? 'secondary';

    $acteLabels = [
        'commission_rogatoire' => 'Commission rogatoire',
        'expertise'            => 'Expertise',
        'audition'             => 'Audition',
        'confrontation'        => 'Confrontation',
        'perquisition'         => 'Perquisition',
        'autre'                => 'Autre',
    ];
    $acteStatutColors = [
        'en_attente' => 'secondary',
        'en_cours'   => 'warning',
        'execute'    => 'success',
        'annule'     => 'danger',
    ];
@endphp

@extends($layout)

@section('content')
<div class="container-fluid py-4">

    @foreach(['success','error'] as $type)
    @if(session($type))
        <div class="alert alert-{{ $type === 'error' ? 'danger' : 'success' }} alert-dismissible fade show">
            {{ session($type) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @endforeach

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Instruction judiciaire</h4>
            <small class="text-muted">
                {{ $instruction->dossier->numero_rp }} —
                {{ $instruction->dossier->registre->nom ?? '' }} —
                Saisine le {{ \Carbon\Carbon::parse($instruction->date_saisine)->format('d/m/Y') }}
            </small>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge bg-{{ $sc }} fs-6">
                {{ $statutLabels[$instruction->statut] ?? $instruction->statut }}
            </span>
            <a href="{{ route($backRoute) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- Colonne gauche --}}
        <div class="col-lg-4">

            {{-- Infos générales --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white"><h6 class="mb-0">Informations</h6></div>
                <div class="card-body small">
                    <dl class="row mb-0">
                        <dt class="col-5">Dossier</dt>
                        <dd class="col-7">
                            <a href="{{ route('dossiers.show', $instruction->dossier) }}">
                                {{ $instruction->dossier->numero_registre }}
                            </a>
                        </dd>
                        <dt class="col-5">Juge</dt>
                        <dd class="col-7">{{ $instruction->juge->name ?? '—' }}</dd>
                        <dt class="col-5">Procureur</dt>
                        <dd class="col-7">{{ $instruction->procureur->name ?? '—' }}</dd>
                        <dt class="col-5">Infraction</dt>
                        <dd class="col-7">{{ $instruction->dossier->nature_infraction ?: '—' }}</dd>
                        @if($instruction->date_cloture)
                        <dt class="col-5">Clôturée le</dt>
                        <dd class="col-7">{{ \Carbon\Carbon::parse($instruction->date_cloture)->format('d/m/Y') }}</dd>
                        <dt class="col-5">Motif</dt>
                        <dd class="col-7">{{ $instruction->motif_cloture }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Parties --}}
            @if($instruction->dossier->parties->isNotEmpty())
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white"><h6 class="mb-0">Parties</h6></div>
                <ul class="list-group list-group-flush">
                    @foreach($instruction->dossier->parties as $p)
                    <li class="list-group-item small">
                        <span class="fw-semibold">{{ $p->nom }} {{ $p->prenom }}</span>
                        <span class="badge bg-light border text-dark ms-1">{{ $p->role }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Détention provisoire --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Détention provisoire</h6>
                    @if($instruction->isEnCours())
                        <button class="btn btn-sm btn-outline-danger"
                            data-bs-toggle="collapse" data-bs-target="#formDetention">
                            <i class="fas fa-plus"></i>
                        </button>
                    @endif
                </div>

                @if($instruction->isEnCours())
                <div class="collapse" id="formDetention">
                    <div class="card-body border-top">
                        <form method="POST" action="{{ route('detention.store', $instruction) }}">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small fw-semibold">Début</label>
                                <input type="date" name="date_debut" class="form-control form-control-sm"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-semibold">Échéance légale</label>
                                <input type="date" name="date_echeance" class="form-control form-control-sm">
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-semibold">Motif</label>
                                <textarea name="motif" rows="2" class="form-control form-control-sm"
                                    placeholder="Motif de la détention..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger btn-sm w-100">Enregistrer</button>
                        </form>
                    </div>
                </div>
                @endif

                <div class="card-body p-0">
                    @forelse($instruction->detentions as $detention)
                    @php $alerte = $detention->alerteNiveau(); @endphp
                    <div class="p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-semibold">
                                {{ \Carbon\Carbon::parse($detention->date_debut)->format('d/m/Y') }}
                                → {{ \Carbon\Carbon::parse($detention->date_echeance)->format('d/m/Y') }}
                            </span>
                            @if($detention->statut !== 'levee')
                            <span class="badge bg-{{ $alerte }} {{ $alerte==='warning'?'text-dark':'' }}">
                                @if($alerte === 'danger') DÉPASSÉ
                                @elseif($alerte === 'warning') {{ $detention->joursRestants() }} j restants
                                @else {{ $detention->joursRestants() }} j restants
                                @endif
                            </span>
                            @else
                            <span class="badge bg-secondary">Levée</span>
                            @endif
                        </div>
                        <div class="small text-muted">{{ Str::limit($detention->motif, 80) }}</div>
                        @if($detention->statut !== 'levee' && $instruction->isEnCours())
                        <div class="d-flex gap-2 mt-2">
                            <form method="POST" action="{{ route('detention.lever', $detention) }}">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-success"
                                    onclick="return confirm('Lever la détention ?')">
                                    Lever
                                </button>
                            </form>
                            <button class="btn btn-sm btn-outline-warning text-dark"
                                data-bs-toggle="collapse"
                                data-bs-target="#prolonger-{{ $detention->id }}">
                                Prolonger
                            </button>
                        </div>
                        <div class="collapse mt-2" id="prolonger-{{ $detention->id }}">
                            <form method="POST" action="{{ route('detention.prolonger', $detention) }}">
                                @csrf @method('PATCH')
                                <input type="date" name="date_echeance"
                                    class="form-control form-control-sm mb-1">
                                <textarea name="motif" rows="2"
                                    class="form-control form-control-sm mb-1"
                                    placeholder="Motif de la prolongation..."></textarea>
                                <button type="submit" class="btn btn-warning btn-sm text-dark w-100">
                                    Confirmer
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="text-muted text-center py-3 small">Aucune détention provisoire</div>
                    @endforelse
                </div>
            </div>

            {{-- Clôture de l'instruction --}}
            @if($instruction->isEnCours() && $user?->hasRole('juge'))
            <div class="card shadow-sm border-warning border-2">
                <div class="card-header bg-warning bg-opacity-10">
                    <h6 class="mb-0 text-warning">Clôturer l'instruction</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('instructions.clore', $instruction) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Décision <span class="text-danger">*</span></label>
                            <select name="statut" class="form-select form-select-sm">
                                <option value="clos_renvoi">Renvoi en audience (ordonnance de renvoi)</option>
                                <option value="clos_non_lieu">Non-lieu (ordonnance de non-lieu)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Date de clôture <span class="text-danger">*</span></label>
                            <input type="date" name="date_cloture" class="form-control form-control-sm"
                                value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Motif <span class="text-danger">*</span></label>
                            <textarea name="motif_cloture" rows="3" class="form-control form-control-sm"
                                placeholder="Exposer les raisons de la clôture..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning text-dark btn-sm w-100"
                            onclick="return confirm('Clôturer l\'instruction ?')">
                            <i class="fas fa-gavel"></i> Clôturer
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        {{-- Colonne droite --}}
        <div class="col-lg-8">

            {{-- Actes d'instruction --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">Actes d'instruction</h5>
                    @if($instruction->isEnCours())
                    <button class="btn btn-sm btn-outline-primary"
                        data-bs-toggle="collapse" data-bs-target="#formActe">
                        <i class="fas fa-plus"></i> Nouvel acte
                    </button>
                    @endif
                </div>

                @if($instruction->isEnCours())
                <div class="collapse" id="formActe">
                    <div class="card-body border-top bg-light">
                        <form method="POST" action="{{ route('actes.store', $instruction) }}">
                            @csrf
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Type d'acte</label>
                                    <select name="type_acte" class="form-select form-select-sm">
                                        @foreach($acteLabels as $val => $label)
                                        <option value="{{ $val }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">Date demande</label>
                                    <input type="date" name="date_demande"
                                        class="form-control form-control-sm"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">Échéance</label>
                                    <input type="date" name="date_echeance"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-semibold">Objet / Description</label>
                                <textarea name="objet" rows="2" class="form-control form-control-sm"
                                    placeholder="Décrire l'acte d'instruction..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                <div class="card-body p-0">
                    @forelse($instruction->actes as $acte)
                    @php
                    $asc = $acteStatutColors[$acte->statut] ?? 'secondary';
                    $enRetard = $acte->isEnRetard();
                    @endphp
                    <div class="p-3 border-bottom {{ $enRetard ? 'bg-danger bg-opacity-5' : '' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="fw-semibold small">
                                    {{ $acteLabels[$acte->type_acte] ?? $acte->type_acte }}
                                </span>
                                @if($enRetard)
                                    <span class="badge bg-danger ms-1">En retard</span>
                                @endif
                                <div class="text-muted small mt-1">{{ $acte->objet }}</div>
                                <div class="text-muted" style="font-size:0.75rem">
                                    Demandé le {{ $acte->date_demande->format('d/m/Y') }}
                                    @if($acte->date_echeance)
                                        — Échéance : {{ $acte->date_echeance->format('d/m/Y') }}
                                    @endif
                                    — Par : {{ $acte->demandeur->name ?? '—' }}
                                </div>
                                @if($acte->resultat)
                                <div class="mt-1 p-2 bg-light rounded small">
                                    <i class="fas fa-check-circle text-success me-1"></i>
                                    {{ $acte->resultat }}
                                </div>
                                @endif
                            </div>
                            <span class="badge bg-{{ $asc }} {{ $asc==='warning'?'text-dark':'' }}">
                                {{ ucfirst(str_replace('_',' ',$acte->statut)) }}
                            </span>
                        </div>

                        @if($instruction->isEnCours() && !in_array($acte->statut, ['execute','annule']))
                        <form method="POST" action="{{ route('actes.statut', $acte) }}"
                              class="d-flex gap-2 mt-2 align-items-end">
                            @csrf @method('PATCH')
                            <select name="statut" class="form-select form-select-sm" style="width:auto">
                                <option value="en_attente" {{ $acte->statut==='en_attente'?'selected':'' }}>En attente</option>
                                <option value="en_cours"   {{ $acte->statut==='en_cours'?'selected':'' }}>En cours</option>
                                <option value="execute"    {{ $acte->statut==='execute'?'selected':'' }}>Exécuté</option>
                                <option value="annule"     {{ $acte->statut==='annule'?'selected':'' }}>Annulé</option>
                            </select>
                            <textarea name="resultat" rows="1" class="form-control form-control-sm"
                                placeholder="Résultat (facultatif)...">{{ $acte->resultat }}</textarea>
                            <input type="date" name="date_execution"
                                class="form-control form-control-sm"
                                value="{{ $acte->date_execution?->format('Y-m-d') }}">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-save"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                    @empty
                    <div class="text-muted text-center py-4 small">
                        Aucun acte d'instruction enregistré
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Messagerie interne --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-dark">Messagerie interne</h5>
                </div>
                <div class="card-body p-0">
                    <div style="max-height: 320px; overflow-y: auto;" class="p-3">
                        @forelse($instruction->messages as $msg)
                        @php $estMoi = $msg->expediteur_id === auth()->id(); @endphp
                        <div class="mb-3 {{ $estMoi ? 'text-end' : '' }}">
                            <div class="d-inline-block text-start {{ $estMoi ? 'bg-primary text-white' : 'bg-light' }}
                                rounded p-2 px-3" style="max-width:80%">
                                <div class="fw-semibold small mb-1">
                                    {{ $msg->objet }}
                                </div>
                                <div class="small">{{ $msg->contenu }}</div>
                                <div class="mt-1 opacity-75" style="font-size:0.7rem">
                                    {{ $msg->expediteur->name ?? '—' }} —
                                    {{ $msg->created_at->format('d/m/Y H:i') }}
                                    @if(!$estMoi && $msg->lu)
                                        <i class="fas fa-check-double ms-1"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-muted text-center py-3 small">
                            Aucun message échangé
                        </div>
                        @endforelse
                    </div>

                    <div class="border-top p-3">
                        <form method="POST" action="{{ route('messages.instruction.store', $instruction) }}">
                            @csrf
                            <div class="mb-2">
                                <input type="text" name="objet" class="form-control form-control-sm"
                                    placeholder="Objet du message...">
                            </div>
                            <div class="d-flex gap-2">
                                <textarea name="contenu" rows="2"
                                    class="form-control form-control-sm"
                                    placeholder="Votre message..."></textarea>
                                <button type="submit" class="btn btn-primary btn-sm px-3">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
