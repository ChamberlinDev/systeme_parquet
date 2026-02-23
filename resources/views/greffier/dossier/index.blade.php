@extends('greffier.layout.app')
@section('content')

<div class="container-fluid">

    {{-- Statistiques --}}
    <div class="row">
        <!-- Total Dossiers -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Dossiers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">56</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder-open fa-2x text-primary"></i> <!-- Icône dossier ouvert -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dossiers en cours -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Dossiers en cours</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-success"></i> <!-- Icône en cours -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dossiers traités -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Dossiers traités</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">24</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-info"></i> <!-- Icône dossier traité -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dossiers Archivés -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Dossiers Archivés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">15</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-archive fa-2x text-warning"></i> <!-- Icône dossier archivé -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Tableau des dossiers --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dossiers</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('dossiers.create.form') }}" class="btn btn-primary btn-sm mx-2">
                <i class="fas fa-plus"></i> Ajouter un dossier
            </a>
            <a href="#" class="btn btn-secondary btn-sm">
                <i class="fas fa-file-pdf"></i> Exporter PDF
            </a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des dossiers</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Numéro dossier</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dossiers as $dossier)
                        <tr>
                            <td>{{ $dossier->numero }}</td>
                            <td>
                                <span class="badge {{ $dossier->status == 'pending' ? 'badge-warning' : 'badge-success' }}">
                                    {{ ucfirst($dossier->status) }}
                                </span>
                            </td>
                            <td>{{ $dossier->created_at->format('d.m.Y') }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info">Voir</a>
                                <a href="#" class="btn btn-sm btn-warning">Modifier</a>
                                <form action="#" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Aucun dossier trouvé</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection