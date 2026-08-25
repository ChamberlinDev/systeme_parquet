@extends('admin.layout.app')
@section('content')
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> -->


<div class="row mb-3">
	<div class="col-xl-3 col-md-6 mb-4">
		<div class="card h-100">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold text-uppercase mb-1">Total Dossiers</div>
						<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dossiers->total() }}</div>
					</div>
					<div class="col-auto">
						<i class="fas fa-calendar fa-2x text-primary"></i>
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
						<div class="text-xs font-weight-bold text-uppercase mb-1">Utilisateurs</div>
						<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->count() }}</div>

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
						<div class="text-xs font-weight-bold text-uppercase mb-1">Audiences & decision</div>
						<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">6</div>

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
						<div class="h5 mb-0 font-weight-bold text-gray-800">18</div>

					</div>
					<div class="col-auto">
						<i class="fas fa-comments fa-2x text-warning"></i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-12 col-lg-7 mb-4">
		<div class="card shadow-sm border-0">
			<div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between border-bottom">
				<h6 class="m-0 fw-bold text-primary">
					<i class="fas fa-folder-open me-2"></i>Aperçu des dossiers
				</h6>
				<a class="btn btn-outline-danger btn-sm rounded-pill px-3" href="#">
					Parcourir <i class="fas fa-chevron-right ms-1"></i>
				</a>
			</div>

			<div class="table-responsive">
				<table class="table table-hover align-middle mb-0">
					<thead>
						<tr class="text-uppercase text-muted small">
							<th style="width:30%" class="ps-4">Dossier</th>
							<th>Registre</th>
							<th>Statut</th>
							<th>Date enregistrement</th>
							<th>Greffier</th>
							<th>Procureur</th>
							<th class="text-center pe-4">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($dossiers as $dossier)
						<tr>
							<td class="ps-4">
								<div class="d-flex align-items-center gap-3">
									<div class="icon-folder">
										<i class="fas fa-folder-open"></i>
									</div>
									<div>
										<a href="{{ route('dossiers.show.admin', $dossier->id_dossier) }}" class="text-decoration-none">
											<div class="fw-semibold text-dark">{{ $dossier->numero_registre }}</div>
										</a>
										<small class="text-muted">
											{{ $dossier->parties->count() }} partie(s)
											@if($dossier->files->count())
											· {{ $dossier->files->count() }} fichier(s)
											@endif
										</small>
									</div>
								</div>
							</td>

							<td>
								<span class="badge bg-light border text-dark fw-normal">
									{{ $dossier->registre->nom ?? '—' }}
								</span>
							</td>

							<td>
								@php
								$colors = [
								'En cours' => 'warning',
								'Clôturé' => 'success',
								'Archivé' => 'secondary',
								'Suspendu' => 'danger',
								];
								$color = $colors[$dossier->statut] ?? 'secondary';
								@endphp
								<span class="badge rounded-pill bg-{{ $color }}-subtle text-{{ $color }} border border-{{ $color }}-subtle px-3 py-2">
									{{ $dossier->statut }}
								</span>
							</td>

							<td>
								<div class="fw-medium">{{ \Carbon\Carbon::parse($dossier->date_demande)->format('d/m/Y') }}</div>
								<small class="text-muted">
									{{ \Carbon\Carbon::parse($dossier->date_demande)->diffForHumans() }}
								</small>
							</td>

							<td>
								@if($dossier->greffier)
								<span class="text-dark">{{ $dossier->greffier->name }}</span>
								@else
								<span class="text-muted fst-italic">Non assigné</span>
								@endif
							</td>

							<td>
								@if($dossier->procureur)
								<span class="text-dark">{{ $dossier->procureur->name }}</span>
								@else
								<span class="text-muted fst-italic">Non assigné</span>
								@endif
							</td>

							<td class="text-center pe-4">
								<div class="d-flex justify-content-center gap-1">
									<a href="{{ route('dossiers.show.admin', $dossier->id_dossier) }}" class="btn btn-sm btn-light text-primary" title="Voir">
										<i class="fas fa-eye"></i>
									</a>
									<a href="{{ route('dossiers.edit', $dossier->id_dossier) }}" class="btn btn-sm btn-light text-warning" title="Modifier">
										<i class="fas fa-pen"></i>
									</a>
									<form action="#" method="POST" class="d-inline">
										@csrf
										@method('DELETE')
										<button class="btn btn-sm btn-light text-danger" title="Supprimer"
											onclick="return confirm('Supprimer ce dossier ?')">
											<i class="fas fa-trash"></i>
										</button>
									</form>
								</div>
							</td>
						</tr>
						@empty
						<tr>
							<td colspan="7" class="text-center py-5 text-muted">
								<i class="fas fa-folder-open fa-3x mb-3 opacity-25 d-block"></i>
								Aucun dossier trouvé
							</td>
						</tr>
						@endforelse
					</tbody>
				</table>
			</div>
			<div class="card-footer bg-white"></div>
		</div>
	</div>

	<div class="col-xl-12 col-lg-7 mb-3">
		<div class="card shadow-sm border-0">
			<div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between border-bottom">
				<h6 class="m-0 fw-bold text-primary">
					<i class="fas fa-users me-2"></i>Aperçu des utilisateurs
				</h6>
				<a class="btn btn-outline-danger btn-sm rounded-pill px-3" href="#">
					Parcourir <i class="fas fa-chevron-right ms-1"></i>
				</a>
			</div>

			<div class="table-responsive">
				<table class="table table-hover align-middle mb-0">
					<thead>
						<tr class="text-uppercase text-muted small">
							<th class="ps-4">Utilisateur</th>
							<th>Email</th>
							<th>Parquet</th>
							<th>Statut</th>
							<th>Rôle(s)</th>
							<th class="text-center pe-4">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($users as $user)
						<tr>
							<td class="ps-4">
								<div class="d-flex align-items-center gap-3">
									<div class="avatar-circle-rounded bg-light text-dark">
										
										{{ $loop->iteration }}
									</div>
									<span class="fw-semibold text-dark">{{ $user->name }}</span>
								</div>
							</td>

							<td class="text-muted">{{ $user->email }}</td>

							<td>
								@if($user->parquet)
								{{ $user->parquet->nom }}
								@else
								<span class="text-muted fst-italic">Global</span>
								@endif
							</td>

							<td>
								@if($user->is_actif)
								<span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2">
									<i class="fas fa-circle fa-xs me-1"></i>Actif
								</span>
								@else
								<span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">
									<i class="fas fa-circle fa-xs me-1"></i>Inactif
								</span>
								@endif
							</td>

							<td>
								@forelse($user->getRoleNames() as $role)
								<span class="badge bg-primary-subtle text-primary border border-primary-subtle me-1">
									{{ ucfirst($role) }}
								</span>
								@empty
								<span class="text-muted fst-italic">Aucun rôle</span>
								@endforelse
							</td>

							<td class="text-center pe-4">
								<div class="d-flex justify-content-center gap-1">
									<a href="{{ route('users.details', $user->id) }}" class="btn btn-sm btn-light text-warning" title="Modifier">
										<i class="fas fa-edit"></i>
									</a>

									<form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
										onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?')">
										@csrf
										@method('DELETE')
										<button type="submit" class="btn btn-sm btn-light text-danger" title="Supprimer">
											<i class="fas fa-trash-alt"></i>
										</button>
									</form>

									<form action="{{ $user->is_actif ? route('users.desactiver', $user->id) : route('users.activer', $user->id) }}"
										method="POST" class="d-inline">
										@csrf
										@method('PATCH')
										<button type="submit" class="btn btn-sm btn-light {{ $user->is_actif ? 'text-secondary' : 'text-success' }}"
											title="{{ $user->is_actif ? 'Désactiver' : 'Activer' }}"
											onclick="return confirm('Confirmer cette action ?')">
											<i class="fas {{ $user->is_actif ? 'fa-user-slash' : 'fa-user-check' }}"></i>
										</button>
									</form>
								</div>
							</td>
						</tr>
						@empty
						<tr>
							<td colspan="6" class="text-center py-5 text-muted">
								<i class="fas fa-users fa-3x mb-3 opacity-25 d-block"></i>
								Aucun utilisateur trouvé
							</td>
						</tr>
						@endforelse
					</tbody>
				</table>
			</div>
			<div class="card-footer bg-white"></div>
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

	<div class="col-xl-12 col-lg-5">
		<div class="card mb-4">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<h6 class="m-0 font-weight-bold text-primary">Statistiques detaillées</h6>
			</div>
			<div class="card-body">
				<div class="mb-3">
					<div class="small text-gray-500">Total Dossiers
						<div class="small float-right"><b>600</b></div>
					</div>

				</div>
				<div class="mb-3">
					<div class="small text-gray-500">Dossiers en cours
						<div class="small float-right"><b>500</b></div>
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
				<div class="mb-3">
					<div class="small text-gray-500">Utilisateurs
						<div class="small float-right"><b>200</b></div>
					</div>

				</div>
			</div>
			<!-- <div class="card-footer text-center">
			<a class="m-0 small text-primary card-link" href="#">View More <i
			class="fas fa-chevron-right"></i></a>
			</div> -->
		</div>
	</div>


</div>

@endsection