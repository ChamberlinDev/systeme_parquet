@extends('greffier.layout.app')

@section('content')

<div class="row mb-3">
	<div class="col-xl-3 col-md-6 mb-4">
		<div class="card h-100">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold text-uppercase mb-1">Total Dossiers</div>
						<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dossier->count() }}</div>
					</div>
					<div class="col-auto">
						<i class="fas fa-file fa-2x text-primary"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-md-6 mb-4">
		<div class="card h-100">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold text-uppercase mb-1">Audiences</div>
						<div class="h5 mb-0 font-weight-bold text-gray-800"></div>

					</div>
					<div class="col-auto">
						<i class="fas fa-users fa-2x text-success"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-md-6 mb-4">
		<div class="card h-100">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold text-uppercase mb-1">Décision</div>
						<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"></div>

					</div>
					<div class="col-auto">
						<i class="fas fa-users fa-2x text-info"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-md-6 mb-4">
		<div class="card h-100">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold text-uppercase mb-1">Archivage</div>
						<div class="h5 mb-0 font-weight-bold text-gray-800"></div>

					</div>
					<div class="col-auto">
						<i class="fas fa-comments fa-2x text-warning"></i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-8 col-lg-7 mb-4">
		<div class="card">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<h6 class="m-0 font-weight-bold text-primary">Aperçu des dossiers</h6>
				<a class="m-0 float-right btn btn-danger btn-sm" href="{{ route('dossiers.index.greffier') }}">Parcourir <i
						class="fas fa-chevron-right"></i></a>
			</div>
			<div class="table-responsive">
				<table class="table table-hover align-middle mb-0">

					<thead class="table-light">
						<tr>
							<th>N° Registre</th>
							<th>N° Affaire</th>
							<th>Type Affaire</th>
							<th>Statut</th>
							<th>Date enregistrement</th>
							<th>Par</th>
							<th class="text-center">Actions</th>
						</tr>
					</thead>

					<tbody>
						@forelse($dossiers as $dossier)
						<tr>

							{{-- NUMÉRO REGISTRE --}}
							<td>
								<div class="d-flex align-items-center gap-3">
									<i class="fas fa-folder-open fa-2x text-warning"></i>
									<div>
										<a href="{{ route('dossiers.show', $dossier->id_dossier) }}"
											class="text-primary fw-semibold text-decoration-none">
											{{ $dossier->numero_rp }}
										</a>
										<div>
											<small class="text-muted">
												· {{ $dossier->parties->count() }} partie(s)
												@if($dossier->files->count())
												· {{ $dossier->files->count() }} fichier(s)
												@endif
											</small>
										</div>
									</div>
								</div>
							</td>

							{{-- Type affaire --}}
							<td>
								<span class="fw-semibold text-secondary"> {{ $dossier->numero_registre }}
								</span>
							</td>

							{{-- Nom affaire --}}
							<td>
								<span class="badge bg-light border text-dark">
									{{ $dossier->registre->nom ?? '—' }}
								</span>
							</td>

							{{-- STATUT --}}
							<td>
								@php
								$colors = [
								'En cours' => 'warning',
								'Clôturé' => 'success',
								'Archivé' => 'secondary',
								'Suspendu' => 'danger',
								'Orienté' => 'info',
								];
								$color = $colors[$dossier->statut] ?? 'secondary';
								@endphp
								<span class="badge bg-{{ $color }}">{{ $dossier->statut }}</span>
							</td>

							{{-- DATE --}}
							<td>
								<div>{{ \Carbon\Carbon::parse($dossier->date_demande)->format('d/m/Y') }}</div>
								<small class="text-muted">
									{{ \Carbon\Carbon::parse($dossier->date_demande)->diffForHumans() }}
								</small>
							</td>

							{{-- GREFFIER --}}
							<td>{{ $dossier->greffier->name ?? '—' }}</td>

							{{-- ACTIONS --}}
							<td class="text-center">
								<div class="d-flex justify-content-center gap-2">
									<a href="{{ route('dossiers.show', $dossier->id_dossier) }}"
										class="btn btn-sm btn-info" title="Voir">
										<i class="fas fa-eye"></i>
									</a>
									<a href="{{ route('dossiers.edit', $dossier->id_dossier) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
										<i class="fas fa-pen"></i>
									</a>

									<form action="{{ route('dossiers.destroy', $dossier->id_dossier) }}" method="POST" class="d-inline">
										@csrf
										@method('DELETE')
										<a class="btn btn-danger btn-outline-danger" title="Supprimer"
											onclick="return confirm('Supprimer ce dossier ?')">
											<i class="fas fa-trash"></i>
										</a>
									</form>

									<a href="#" class="btn btn-success" title="Envoyer/Transferer">
										<i class="fas fa-paper-plane"></i>
									</a>
								</div>
							</td>

						</tr>
						@empty
						<tr>
							<td colspan="7" class="text-center py-5 text-muted">
								<i class="fas fa-folder-open fa-3x mb-3 opacity-25 d-block"></i>
								Aucun dossier trouvé
								<div class="mt-3">
									<a href="{{ route('dossiers.create.form') }}" class="btn btn-primary btn-sm">
										Créer un dossier
									</a>
								</div>
							</td>
						</tr>
						@endforelse
					</tbody>

				</table>
			</div>
		</div>
		<div class="card-footer"></div>

	</div>

	<div class="col-xl-4 col-lg-5">
		<div class="card mb-4">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<h6 class="m-0 font-weight-bold text-primary">Statistiques detaillées</h6>
			</div>
			<div class="card-body">
				<div class="mb-3">
					<div class="small text-gray-500">Total Dossiers
						<div class="small float-right"><b>{{ $dossier->count() }}</b></div>
					</div>

				</div>
				<div class="mb-3">
					<div class="small text-gray-500">Dossiers en cours
						<div class="small float-right"><b>{{$dossier->count()}}</b></div>
					</div>

				</div>
				<div class="mb-3">
					<div class="small text-gray-500">Dossiers traités
						<div class="small float-right"><b>455</b></div>
					</div>
				</div>
				<div class="mb-3">
					<div class="small text-gray-500">Dossiers archivés
						<div class="small float-right"><b>400</b></div>
					</div>

				</div>
				<!-- <div class="mb-3">
					<div class="small text-gray-500">Utilisateurs
						<div class="small float-right"><b>200</b></div>
					</div>

				</div> -->
			</div>
			<!-- <div class="card-footer text-center">
			<a class="m-0 small text-primary card-link" href="#">View More <i
			class="fas fa-chevron-right"></i></a>
			</div> -->
		</div>
	</div>
	<div class="col-xl-8 col-lg-7">
		<div class="card mb-4">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<h6 class="m-0 font-weight-bold text-primary">Statistiques</h6>

			</div>

			<div class="card-body">
				<div class="chart-area">
					<canvas id="myAreaChart"></canvas>
				</div>
			</div>
		</div>
	</div>
	

</div>
@endsection