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

	<div class="col-xl-8 col-lg-7 mb-4">
		<div class="card">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<h6 class="m-0 font-weight-bold text-primary">Aperçu des dossiers</h6>
				<a class="m-0 float-right btn btn-danger btn-sm" href="#">Parcourir <i
						class="fas fa-chevron-right"></i></a>
			</div>
			<div class="table-responsive">
				<table class="table align-items-center table-flush">
					<thead class="thead-light">
						<tr>
							<th>Order ID</th>
							<th>Customer</th>
							<th>Item</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><a href="#">RA0449</a></td>
							<td>Udin Wayang</td>
							<td>Nasi Padang</td>
							<td><span class="badge badge-success">Delivered</span></td>
							<td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>
						</tr>


					</tbody>
				</table>
			</div>
			<div class="card-footer"></div>
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
	<div class="col-xl-4 col-lg-5">
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