@extends('procureur.layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Ouvrir une instruction judiciaire</h4>
            <small class="text-muted">{{ $dossier->numero_rp }} — {{ $dossier->numero_registre }}</small>
        </div>
        <a href="{{ route('dossiers.show', $dossier) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white"><h6 class="mb-0">Dossier concerné</h6></div>
                <div class="card-body small">
                    <dl class="row mb-0">
                        <dt class="col-4">Registre</dt>
                        <dd class="col-8">{{ $dossier->registre->nom ?? '-' }}</dd>
                        <dt class="col-4">Infraction</dt>
                        <dd class="col-8">{{ $dossier->nature_infraction ?: '—' }}</dd>
                        <dt class="col-4">Orientation</dt>
                        <dd class="col-8">
                            <span class="badge bg-dark">Réquisitoire introductif</span>
                        </dd>
                        <dt class="col-4">Motif</dt>
                        <dd class="col-8">{{ $dossier->motif_orientation }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white"><h6 class="mb-0">Saisine du juge d'instruction</h6></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('instructions.store', $dossier) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Juge d'instruction <span class="text-danger">*</span>
                            </label>
                            <select name="id_juge"
                                class="form-select @error('id_juge') is-invalid @enderror">
                                <option value="">-- Sélectionner un juge --</option>
                                @foreach($juges as $juge)
                                <option value="{{ $juge->id }}"
                                    {{ old('id_juge') == $juge->id ? 'selected' : '' }}>
                                    {{ $juge->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_juge')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Date de saisine <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="date_saisine"
                                class="form-control @error('date_saisine') is-invalid @enderror"
                                value="{{ old('date_saisine', date('Y-m-d')) }}">
                            @error('date_saisine')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark">
                                <i class="fas fa-balance-scale"></i> Ouvrir l'instruction
                            </button>
                            <a href="{{ route('dossiers.show', $dossier) }}"
                               class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
