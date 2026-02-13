	<div class="sidebar">
		<div class="scrollbar-inner sidebar-wrapper">
			<div class="user">

				<div class="info">
					<a class="" data-toggle="collapse" href="#collapseExample" aria-expanded="true">
						<span>
							Juge
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

						</ul>
					</div>
				</div>
			</div>
			<ul class="nav">

				<li class="nav-item {{ request()->routeIs('accueil.juge') ? 'active' : '' }}">
					<a href="{{ route('accueil.juge') }}">
						<i class="la la-dashboard"></i>
						<p>Tableau de bord</p>
						<span class="badge badge-count">5</span>
					</a>
				</li>

				<li class="nav-item {{ request()->routeIs('dossiers.index.juge') ? 'active' : '' }}">
					<a href="{{ route('dossiers.index.juge') }}">
						<i class="la la-folder"></i>
						<p>Dossiers</p>
						<span class="badge badge-count">50</span>
					</a>
				</li>

				<li class="nav-item {{ request()->is('juge/archives*') ? 'active' : '' }}">
					<a href="#">
						<i class="la la-folder-open"></i>
						<p>Archivages</p>
						<span class="badge badge-count">6</span>
					</a>
				</li>

				<li class="nav-item {{ request()->is('juge/notifications*') ? 'active' : '' }}">
					<a href="#">
						<i class="la la-bell"></i>
						<p>Notifications</p>
						<span class="badge badge-success">3</span>
					</a>
				</li>

				<li class="nav-item {{ request()->is('juge/audiences*') ? 'active' : '' }}">
					<a href="#">
						<i class="la la-folder"></i>
						<p>Audiences</p>
						<span class="badge badge-count">50</span>
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