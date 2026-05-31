{{--
    Formulaire de recherche / filtrage des dossiers.
    Paramètres attendus : $filtres, $registres, $statuts (optionnel), $route (route name)
--}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route($route) }}" id="formRecherche">
            <div class="row g-2 align-items-end">

                {{-- Mot-clé --}}
                <div class="col-lg-4 col-md-6">
                    <label class="form-label small fw-semibold mb-1">Recherche</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="q" class="form-control"
                            value="{{ $filtres['q'] ?? '' }}"
                            placeholder="N° dossier, partie, infraction…">
                    </div>
                </div>

                {{-- Registre --}}
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small fw-semibold mb-1">Registre</label>
                    <select name="registre" class="form-select form-select-sm">
                        <option value="">Tous</option>
                        @foreach($registres as $reg)
                        <option value="{{ $reg->id_registre }}"
                            {{ ($filtres['registre'] ?? '') == $reg->id_registre ? 'selected' : '' }}>
                            {{ $reg->nom }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Statut (optionnel) --}}
                @if(!empty($statuts))
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small fw-semibold mb-1">Statut</label>
                    <select name="statut" class="form-select form-select-sm">
                        <option value="">Tous</option>
                        @foreach($statuts as $s)
                        <option value="{{ $s }}"
                            {{ ($filtres['statut'] ?? '') === $s ? 'selected' : '' }}>
                            {{ $s }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Date du --}}
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small fw-semibold mb-1">Du</label>
                    <input type="date" name="date_du" class="form-control form-control-sm"
                        value="{{ $filtres['date_du'] ?? '' }}">
                </div>

                {{-- Date au --}}
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small fw-semibold mb-1">Au</label>
                    <input type="date" name="date_au" class="form-control form-control-sm"
                        value="{{ $filtres['date_au'] ?? '' }}">
                </div>

                {{-- Boutons --}}
                <div class="col-lg-12 col-md-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    @php $hasFilter = collect($filtres)->filter()->isNotEmpty(); @endphp
                    @if($hasFilter)
                    <a href="{{ route($route) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                    <span class="badge bg-info align-self-center">
                        Filtre actif
                    </span>
                    @endif
                </div>

            </div>
        </form>
    </div>
</div>
