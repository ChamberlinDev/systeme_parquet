	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">
	<div class="sidebar">
		<div class="scrollbar-inner sidebar-wrapper">
			<div class="user">
				<div class="photo">
					<i class="la la-user-circle align-middle mr-1" style="font-size: 26px;"></i>

				</div>
				<div class="info">
					<a class="" data-toggle="collapse" href="#collapseExample" aria-expanded="true">
						<span>
							Greffier
							<span class="user-level">{{auth()->user()->name}}</span>
							<span class="caret"></span>
						</span>
					</a>
					<div class="clearfix"></div>

					<div class="collapse in" id="collapseExample" aria-expanded="true">
						<ul class="nav">
							<li>
								<a href="{{ route('profil.voir') }}">
									<span class="link-collapse">Mon profil</span>
								</a>
							</li>
							<li>

						</ul>
					</div>
				</div>
			</div>
			<ul class="nav">

				<li class="nav-item {{ request()->routeIs('accueil.greffier') ? 'active' : '' }}">
					<a href="{{ route('accueil.greffier') }}">
						<i class="la la-dashboard"></i>
						<p>Tableau de bord</p>
						<span class="badge badge-count">5</span>
					</a>
				</li>

				<li class="nav-item {{ request()->routeIs('dossiers.index.greffier') ? 'active' : '' }}">
					<a href="{{ route('dossiers.index.greffier') }}">
						<i class="la la-folder"></i>
						<p>Dossiers</p>
						<span class="badge badge-count">50</span>
					</a>
				</li>

				<li class="nav-item {{ request()->is('archives*') ? 'active' : '' }}">
					<a href="#">
						<i class="la la-folder-open"></i>
						<p>Archivages</p>
						<span class="badge badge-count">6</span>
					</a>
				</li>

				<li class="nav-item {{ request()->is('registres*') ? 'active' : '' }}">
					<a href="{{ route('registres.index') }}">
						<i class="la la-book"></i>
						<p>Registres</p>
						<span class="badge badge-success">3</span>
					</a>
				</li>

				<li class="nav-item update-pro">
					<a href="{{ route('logout') }}" class="btn btn-danger text-white w-100">
						<i class="la la-logout"></i>
						<p>Déconnexion</p>
					</a>
				</li>

			</ul>

		</div>
	</div>