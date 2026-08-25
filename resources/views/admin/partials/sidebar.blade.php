<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">

  {{-- Logo --}}
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/accueil_admin">
    <div class="sidebar-brand-icon">
      <img src="{{ asset('img/logo/logo2.png') }}" alt="Logo">
    </div>
    <div class="sidebar-brand-text mx-3">GS. Parquet</div>
  </a>

  <hr class="sidebar-divider my-0">

  {{-- Dashboard --}}
  <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <a class="nav-link" href="#">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Espace de travail</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  <div class="sidebar-heading">
    Fonctionnalités
  </div>

  {{-- Nouveau dossier --}}
  <li class="nav-item {{ request()->routeIs('dossiers.create') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('dossiers.index') }}">
      <i class="fas fa-fw fa-folder"></i>
      <span>Nouveau dossier</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">
      <i class="fas fa-fw fa-clipboard"></i>
      <span>Instructions et Suivi</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">
      <i class="fas fa-fw fa-gavel"></i>
      <span>Instructions judicaires</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">
      <i class="fas fa-fw fa-calendar-check"></i>
      <span>Audiences</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">
      <i class="fas fa-fw fa-folder"></i>
      <span>Statistiques</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">
      <i class="fas fa-fw fa-clipboard-check"></i>
      <span>Executions des decisions</span>
    </a>
  </li>
  <li class="nav-item" >
    <a class="nav-link" href="#">
      <i class="fas fa-fw fa-archive"></i>
      <span>Archives</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  <div class="sidebar-heading">
    Système
  </div>

  {{-- Utilisateurs --}}
  <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('users.index') }}">
      <i class="fas fa-fw fa-users"></i>
      <span>Utilisateurs</span>
    </a>
  </li>

  {{-- Parquets --}}
  <li class="nav-item {{ request()->routeIs('parquets.*') ? 'active' : '' }}">
    <a class="nav-link" href="/liste_parquets">
      <i class="fas fa-fw fa-home"></i>
      <span>Parquets</span>
    </a>
  </li>

  {{-- Paramètres --}}
  <li class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
    <a class="nav-link" href="#">
      <i class="fas fa-fw fa-cog"></i>
      <span>Paramètres</span>
    </a>
  </li>

  <hr class="sidebar-divider d-none d-md-block">

  {{-- Déconnexion --}}
  <li class="nav-item px-3 mt-2">
    <a class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center justify-content-center"
      href="javascript:void(0);" data-toggle="modal" data-target="#logoutModal">
      <i class="fas fa-sign-out-alt fa-sm me-2"></i>
      Déconnexion
    </a>
  </li>

</ul>