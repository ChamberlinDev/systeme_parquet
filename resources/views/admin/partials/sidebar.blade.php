<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">

  {{-- Logo --}}
  <a class="sidebar-brand d-flex align-items-center justify-content-center"
     href="/accueil_admin">
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

  {{-- Instruction et suivi --}}
  <li class="nav-item {{ request()->routeIs('instructions.*') ? 'active' : '' }}">
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

  {{-- Préparation audience --}}
  <li class="nav-item {{ request()->routeIs('audiences.preparation') ? 'active' : '' }}">
    <a class="nav-link" href="#">
      <i class="fas fa-fw fa-calendar-alt"></i>
      <span>Préparation audience</span>
    </a>
  </li>

  {{-- Audience et décision --}}
  <li class="nav-item {{ request()->routeIs('audiences.index') ? 'active' : '' }}">
    <a class="nav-link" href="#">
      <i class="fas fa-fw fa-calendar-check"></i>
      <span>Audience et décision</span>
    </a>
  </li>

  {{-- Exécution --}}
  <li class="nav-item {{ request()->routeIs('executions.*') ? 'active' : '' }}">
    <a class="nav-link" href="#">
      <i class="fas fa-fw fa-clipboard-check"></i>
      <span>Exécution des décisions</span>
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

  <hr class="sidebar-divider">

  {{-- Déconnexion sécurisée --}}
  <li class="nav-item px-3">
   <div class="dropdown-divider"></div>
 				<a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#logoutModal">
 					<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger-400"></i>
 					Deconnexion
 				</a>
  </li>

  <!-- <div class="version" id="version-ruangadmin"></div> -->

</ul>
