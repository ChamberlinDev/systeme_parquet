<aside class="sidebar">
	<div class="sidebar-start">
		<div class="sidebar-head">
			<a href="/accueil_greffier" class="logo-wrapper" title="Home">
				<span class="sr-only">Home</span>
				<!-- <span class="icon logo" aria-hidden="true"></span> -->
				<div class="logo-text">
					<span class="logo-title">G.S PARQUET</span>
					<span class="logo-subtitle">Dashboard</span>
				</div>

			</a>
			<button class="sidebar-toggle transparent-btn" title="Menu" type="button">
				<span class="sr-only">Toggle menu</span>
				<span class="icon menu-toggle" aria-hidden="true"></span>
			</button>
		</div>
		<div class="sidebar-body">
			<ul class="sidebar-body-menu">
				<li>
					<a class="active" href="/accueil_greffier"><span class="icon home" aria-hidden="true"></span>Tableau de bord</a>
				</li>

				<li>
					<a class="show-cat-btn" href="##">
						<span class="icon folder" aria-hidden="true"></span>Dossiers
						<span class="category__btn transparent-btn" title="Open list">
							<span class="sr-only">Open list</span>
							<span class="icon arrow-down" aria-hidden="true"></span>
						</span>
					</a>
					<ul class="cat-sub-menu">
						<li>
							<a href="categories.html">Tous les dossiers</a>
						</li>
					</ul>
				</li>
				<li>
					<a class="show-cat-btn" href="##">
						<span class="icon image" aria-hidden="true"></span>Audiences
						<span class="category__btn transparent-btn" title="Open list">
							<span class="sr-only">Open list</span>
							<span class="icon arrow-down" aria-hidden="true"></span>
						</span>
					</a>
					<ul class="cat-sub-menu">
						<li>
							<a href="media-01.html">Audiences en attente</a>
						</li>
						<li>
							<a href="media-02.html">Audiences rendues</a>
						</li>
					</ul>
				</li>
				<li>
					<a class="show-cat-btn" href="##">
						<span class="icon paper" aria-hidden="true"></span>Decisions
						<span class="category__btn transparent-btn" title="Open list">
							<span class="sr-only">Open list</span>
							<span class="icon arrow-down" aria-hidden="true"></span>
						</span>
					</a>
					<ul class="cat-sub-menu">
						<li>
							<a href="pages.html">toutes les decisions</a>
						</li>
						<li>
							<a href="pages.html">Decisions rendues</a>
					</ul>
				</li>


			</ul>
			<span class="system-menu__title">Systeme</span>
			<ul class="sidebar-body-menu">


				<li>
					<a href="##"><span class="icon setting" aria-hidden="true"></span>Paramètres</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="sidebar-footer">
		<a href="{{ route('logout') }}" class="sidebar-user">
			<span class="sidebar-user-img">
				<picture>
					<source srcset="./img/avatar/avatar-illustrated-02.webp" type="image/webp"><img src="./img/avatar/avatar-illustrated-02.png" alt="User name">
				</picture>
			</span>
			<div class="sidebar-user-info">
				<span class="sidebar-user__title">Deconnexion</span>
				<span class="sidebar-user__subtitle">Quitter</span>
			</div>
		</a>
	</div>
</aside>