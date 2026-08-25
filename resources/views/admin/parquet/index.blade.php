@extends('admin.layout.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<div class="container-fluid py-4">

    {{-- ===================== STATISTIQUES ===================== --}}
    <div class="row g-4 mb-4">

        @php
        $total = $parquets->count();
        @endphp

        {{-- Total --}}
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Total parquets</div>
                        <div class="fs-4 fw-bold text-dark">{{ $total }}</div>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center" style="width:50px; height:50px; flex-shrink:0;">
                        <i class="fas fa-home fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ===================== EN-TÊTE ===================== --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-dark mb-0">Liste des parquets</h4>
        <a href="/parquets" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Ajouter un parquet
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success text-dark d-flex align-items-center gap-2">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- ===================== TABLEAU ===================== --}}
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="text-uppercase text-muted small">
                        <th class="ps-4">#</th>
                        <th>Nom</th>
                        <th>Ville</th>
                        <th>Adresse</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th class="text-center pe-4">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($parquets as $parquet)
                    <tr>
                        <td class="ps-4 text-muted">{{ $parquet->id }}</td>

                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center" style="width:40px; height:40px; flex-shrink:0;">
                                    <i class="fas fa-home"></i>
                                </div>
                                <span class="fw-semibold text-dark">{{ $parquet->nom }}</span>
                            </div>
                        </td>

                        <td>
                            <span class="badge bg-light border text-dark fw-normal">
                                <i class="fas fa-map-marker-alt me-1 text-muted"></i>{{ $parquet->ville }}
                            </span>
                        </td>

                        <td class="text-muted">{{ $parquet->adresse }}</td>

                        <td class="text-muted">
                            <i class="fas fa-phone-alt me-1 opacity-50"></i>{{ $parquet->telephone }}
                        </td>

                        <td class="text-muted">
                            <i class="fas fa-envelope me-1 opacity-50"></i>{{ $parquet->email }}
                        </td>

                        <td class="text-center pe-4">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('parquets.edit', $parquet->id) }}" class="btn btn-sm btn-light text-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('parquets.destroy', $parquet->id) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Voulez-vous vraiment supprimer ce parquet ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-home fa-3x mb-3 opacity-25 d-block"></i>
                            Aucun parquet trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection