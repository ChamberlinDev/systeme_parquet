@extends('externe.layout.app')
@php $roleLabel = 'Police Judiciaire'; $homeRoute = route('accueil.pj'); @endphp

@section('sidebar_links')
<li class="nav-item">
    <a class="nav-link" href="{{ route('accueil.pj') }}">
        <i class="fas fa-fw fa-tasks"></i>
        <span>Mes actes à exécuter</span>
    </a>
</li>
<li class="nav-item active">
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
    <h4 class="fw-bold text-dark mb-4">Dépôt sécurisé de pièces</h4>

    <div class="row">
        {{-- Formulaire de dépôt --}}
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white"><h6 class="mb-0 fw-semibold">Nouveau dépôt</h6></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('pj.depot.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Dossier <span class="text-danger">*</span></label>
                            <select name="id_dossier" class="form-select @error('id_dossier') is-invalid @enderror">
                                <option value="">-- Sélectionner --</option>
                                @foreach($dossiers as $d)
                                <option value="{{ $d->id_dossier }}" {{ old('id_dossier') == $d->id_dossier ? 'selected' : '' }}>
                                    {{ $d->numero_registre }} — {{ $d->registre->nom ?? '' }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_dossier')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Type de pièce <span class="text-danger">*</span></label>
                            <select name="type_document" class="form-select @error('type_document') is-invalid @enderror">
                                <option value="pv">Procès-verbal</option>
                                <option value="photo">Photo</option>
                                <option value="video">Vidéo</option>
                                <option value="expertise">Expertise</option>
                                <option value="autre">Autre</option>
                            </select>
                            @error('type_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <input type="text" name="description" class="form-control"
                                value="{{ old('description') }}" placeholder="Brève description de la pièce">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Fichier <span class="text-danger">*</span></label>
                            <input type="file" name="fichier" class="form-control @error('fichier') is-invalid @enderror">
                            <div class="form-text">PDF, image, vidéo ou document — max 20 Mo</div>
                            @error('fichier')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-upload"></i> Déposer la pièce
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Mes derniers dépôts --}}
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white"><h6 class="mb-0 fw-semibold">Mes derniers dépôts</h6></div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Type</th>
                                <th>Dossier</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mesDocuments as $doc)
                            <tr>
                                <td>
                                    <i class="fas {{ \App\Models\PjDocument::$typeIcons[$doc->type_document] ?? 'fa-file' }} text-primary me-1"></i>
                                    <span class="small">{{ \App\Models\PjDocument::$typeLabels[$doc->type_document] ?? $doc->type_document }}</span>
                                </td>
                                <td class="small fw-semibold">{{ $doc->dossier->numero_registre ?? '—' }}</td>
                                <td class="small text-muted">{{ Str::limit($doc->description ?: $doc->original_name, 30) }}</td>
                                <td class="small text-muted">{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('pj.document.voir', $doc) }}" target="_blank"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 opacity-25 d-block"></i>
                                    Aucun dépôt effectué
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
