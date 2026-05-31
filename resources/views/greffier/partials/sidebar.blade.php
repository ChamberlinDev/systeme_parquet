<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">

  {{-- Logo --}}
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/accueil_greffier">
    <div class="sidebar-brand-icon">
      <img src="{{ asset('img/logo/logo2.png') }}">
    </div>
    <div class="sidebar-brand-text mx-3">GS. Parquet</div>
  </a>

  <hr class="sidebar-divider my-0">

  {{-- Dashboard --}}
  <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <a class="nav-link" href="#">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Tableau de bord</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  <div class="sidebar-heading">
    Fonctionnalités
  </div>

  {{-- Nouveau dossier --}}
  <li class="nav-item {{ request()->routeIs('dossier.create') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('dossiers.index.greffier') }}">
      <i class="fas fa-fw fa-folder"></i>
      <span>Nouveau dossier</span>
    </a>
  </li>

  {{-- Instruction et suivi --}}
  <li class="nav-item {{ request()->routeIs('instruction.index') ? 'active' : '' }}">
    <a class="nav-link" href="#">
      <i class="fas fa-fw fa-clipboard"></i>
      <span>Instruction et suivi</span>
    </a>
  </li>

  {{-- Instruction judiciaire --}}
  <li class="nav-item {{ request()->routeIs('instruction.judiciaire') ? 'active' : '' }}">
    <a class="nav-link" href="#">
      <i class="fas fa-fw fa-gavel"></i>
      <span>Instruction judiciaire</span>
    </a>
  </li>

  {{-- Audiences --}}
  <li class="nav-item {{ request()->routeIs('audiences.index.greffier', 'audiences.create', 'audiences.show') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('audiences.index.greffier') }}">
      <i class="fas fa-fw fa-calendar-check"></i>
      <span>Audiences</span>
    </a>
  </li>

  {{-- Exécution --}}
  <li class="nav-item {{ request()->routeIs('executions.*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('executions.index') }}">
      <i class="fas fa-fw fa-clipboard-check"></i>
      <span>Exécutions</span>
    </a>
  </li>

  {{-- Archives --}}
  <li class="nav-item {{ request()->routeIs('archivage.*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('archivage.index') }}">
      <i class="fas fa-fw fa-archive"></i>
      <span>Archives</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  {{-- Déconnexion sécurisée --}}
  <li class="nav-item px-3">
    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" class="btn btn-danger btn-block">
        Déconnexion
      </button>
    </form>
  </li>

  <div class="version" id="version-ruangadmin"></div>

</ul>
