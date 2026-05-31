@extends('greffier.layout.app')

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Archives</h4>
        <span class="badge bg-secondary fs-6">{{ $dossiers->total() }} dossier(s)</span>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Dossier</th>
                        <th>Registre</th>
                        <th>Parties</th>
                        <th>Date archivage</th>
                        <th>Motif</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dossiers as $dossier)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $dossier->numero_registre }}</div>
                            <small class="text-muted">{{ $dossier->numero_rp }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light border text-dark">
                                {{ $dossier->registre->nom ?? '-' }}
                            </span>
                        </td>
                        <td>
                            @foreach($dossier->parties->take(2) as $p)
                                <div class="small">{{ $p->nom }} {{ $p->prenom }}</div>
                            @endforeach
                            @if($dossier->parties->count() > 2)
                                <small class="text-muted">+{{ $dossier->parties->count() - 2 }} autre(s)</small>
                            @endif
                        </td>
                        <td>
                            {{ $dossier->date_archivage
                                ? \Carbon\Carbon::parse($dossier->date_archivage)->format('d/m/Y')
                                : '—' }}
                        </td>
                        <td>
                            <span class="text-muted small">{{ Str::limit($dossier->motif_archivage, 60) }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('dossiers.show', $dossier) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-archive fa-3x mb-3 opacity-25 d-block"></i>
                            Aucun dossier archivé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($dossiers, 'links'))
        <div class="card-footer bg-white">{{ $dossiers->links() }}</div>
        @endif
    </div>
</div>
@endsection
