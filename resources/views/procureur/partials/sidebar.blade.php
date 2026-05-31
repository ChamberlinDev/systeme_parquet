<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('accueil.procureur') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/logo/logo2.png') }}">
        </div>
        <div class="sidebar-brand-text mx-3">GS. Parquet</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->routeIs('accueil.procureur') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('accueil.procureur') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Fonctionnalites
    </div>

    <li class="nav-item {{ request()->routeIs('dossiers.index.procureur') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dossiers.index.procureur') }}">
            <i class="fas fa-fw fa-folder-open"></i>
            <span>Dossiers</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('dossiers.create.form') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dossiers.create.form') }}">
            <i class="fas fa-fw fa-plus-circle"></i>
            <span>Nouveau dossier</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('audiences.index.procureur', 'audiences.show') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('audiences.index.procureur') }}">
            <i class="fas fa-fw fa-calendar-check"></i>
            <span>Audiences</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('dossiers.orientation.form') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dossiers.index.procureur') }}">
            <i class="fas fa-fw fa-balance-scale"></i>
            <span>Orientations</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('instructions.index.procureur','instructions.show','instructions.create') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('instructions.index.procureur') }}">
            <i class="fas fa-fw fa-search"></i>
            <span>Instructions</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <li class="nav-item px-3">
        <a href="{{ route('logout') }}" class="btn btn-danger btn-block">
            Deconnexion
        </a>
    </li>

    <div class="version" id="version-ruangadmin"></div>
</ul>
