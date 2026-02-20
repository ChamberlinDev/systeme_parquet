<nav class="main-nav--bg">
				<div class="container main-nav">
					<div class="main-nav-start">
						<div class="search-wrapper">
							<i data-feather="search" aria-hidden="true"></i>
							<input type="text" placeholder="Enter keywords ..." required>
						</div>
					</div>
					<div class="main-nav-end">
						<button class="sidebar-toggle transparent-btn" title="Menu" type="button">
							<span class="sr-only">Toggle menu</span>
							<span class="icon menu-toggle--gray" aria-hidden="true"></span>
						</button>
						<div class="lang-switcher-wrapper">
							<button class="lang-switcher transparent-btn" type="button">
								EN
								<i data-feather="chevron-down" aria-hidden="true"></i>
							</button>
							<ul class="lang-menu dropdown">
								<li><a href="##">English</a></li>
								<li><a href="##">French</a></li>
							</ul>
						</div>
						<button class="theme-switcher gray-circle-btn" type="button" title="Switch theme">
							<span class="sr-only">changer de theme</span>
							<i class="sun-icon" data-feather="sun" aria-hidden="true"></i>
							<i class="moon-icon" data-feather="moon" aria-hidden="true"></i>
						</button>
						
						<div class="nav-user-wrapper">
							<button href="##" class="nav-user-btn dropdown-btn" title="My profile" type="button">
								<span class="sr-only">Mon profil</span>
								<span class="nav-user-img">
									<picture>
										<source srcset="./img/avatar/avatar-illustrated-02.webp" type="image/webp"><img src="./img/avatar/avatar-illustrated-02.png" alt="User name">
									</picture>
								</span>
							</button>
							<ul class="users-item-dropdown nav-user-dropdown dropdown">
								<li><a href="{{ route('profil.voir') }}">
										<i data-feather="user" aria-hidden="true"></i>
										<span>Profil</span>
									</a></li>
								<li><a href="#">
										<i data-feather="settings" aria-hidden="true"></i>
										<span>Paramètres</span>
									</a></li>
								<li><a class="danger" href="{{ route('logout') }}"
									   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
										<i data-feather="log-out" aria-hidden="true"></i>
										<span>Se déconnecter</span>
									</a></li>
							</ul>
						</div>
					</div>
				</div>
			</nav>