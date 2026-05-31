<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>GS. Parquet — {{ $roleLabel ?? 'Espace externe' }}</title>
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/ruang-admin.min.css') }}" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">

    {{-- Sidebar minimale --}}
    <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ $homeRoute ?? '#' }}">
            <div class="sidebar-brand-icon">
                <img src="{{ asset('img/logo/logo2.png') }}">
            </div>
            <div class="sidebar-brand-text mx-3">GS. Parquet</div>
        </a>

        <hr class="sidebar-divider my-0">

        <li class="nav-item active">
            <a class="nav-link" href="{{ $homeRoute ?? '#' }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Tableau de bord</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">Mon espace</div>

        @yield('sidebar_links')

        <hr class="sidebar-divider">

        <li class="nav-item px-3">
            <a href="{{ route('logout') }}" class="btn btn-danger btn-block btn-sm">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </li>
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            {{-- Topbar --}}
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="fas fa-user-circle fa-fw"></i>
                            {{ auth()->user()->name }}
                            <span class="badge bg-secondary ms-1">{{ $roleLabel ?? '' }}</span>
                        </span>
                    </li>
                </ul>
            </nav>

            <div class="container-fluid" id="container-wrapper">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>

        @include('admin.partials.footer')
    </div>
</div>

<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/ruang-admin.min.js') }}"></script>
</body>
</html>
