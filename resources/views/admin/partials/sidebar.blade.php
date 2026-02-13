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
								Role
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
									<a href="#">
										<span class="link-collapse">parametres</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<ul class="nav">

					<li class="nav-item {{ request()->is('accueil_admin') ? 'active' : '' }}">
						<a href="/accueil_admin">
							<i class="la la-dashboard"></i>
							<p>Tableau de bord</p>
							<span class="badge badge-count">5</span>
						</a>
					</li>

					<li class="nav-item {{ request()->is('utilisateurs*') ? 'active' : '' }}">
						<a href="/utilisateurs">
							<i class="la la-users"></i>
							<p>Utilisateurs</p>
							<span class="badge badge-count">14</span>
						</a>
					</li>

					<li class="nav-item {{ request()->routeIs('dossiers.index') ? 'active' : '' }}">
						<a href="{{ route('dossiers.index') }}">
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

					<li class="nav-item {{ request()->is('audiences*') ? 'active' : '' }}">
						<a href="#">
							<i class="la la-gavel"></i>
							<p>Audiences et Décisions</p>
							<span class="badge badge-success">3</span>
						</a>
					</li>

					<li class="nav-item {{ request()->is('parquets*') ? 'active' : '' }}">
						<a href="#">
							<i class="la la-home"></i>
							<p>Parquets</p>
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