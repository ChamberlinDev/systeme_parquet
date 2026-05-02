@extends('juge.layout.app')

@section('content')
<div class="row mb-3">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Dossiers a juger</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">32</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-balance-scale fa-2x text-primary"></i>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-gavel fa-2x text-success"></i>
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
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Jugements</div>
                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">18</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-signature fa-2x text-info"></i>
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
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Archives</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">9</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-archive fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Apercu des dossiers</h6>
                <a class="m-0 float-right btn btn-danger btn-sm" href="{{ route('dossiers.index.juge') }}">
                    Parcourir <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th>Numero</th>
                            <th>Registre</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><a href="#">RP/2026/001</a></td>
                            <td>Correctionnelle</td>
                            <td><span class="badge badge-success">En cours</span></td>
                            <td><a href="{{ route('dossiers.index.juge') }}" class="btn btn-sm btn-primary">Voir</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Suivi des audiences</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="small text-gray-500">Affaires en attente
                        <div class="small float-right"><b>14</b></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="small text-gray-500">Deliberes
                        <div class="small float-right"><b>6</b></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="small text-gray-500">Jugements rendus
                        <div class="small float-right"><b>11</b></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="small text-gray-500">Dossiers archives
                        <div class="small float-right"><b>9</b></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
