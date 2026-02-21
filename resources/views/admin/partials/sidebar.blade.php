<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<aside class="sidebar">
	<div class="sidebar-start">
		<div class="sidebar-head">
			<a href="{{ route('accueil.admin') }}" class="logo-wrapper" title="Home">
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
					<a class="active" href="{{ route('accueil.admin') }}"><span class="icon home" aria-hidden="true"></span>Tableau de bord</a>
				</li>
				<li>
					<a class="show-cat-btn" href="##">
						<span class="icon folder" aria-hidden="true"><i class="fa-solid fa-folder"></i>
						</span>
						Dossiers
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
						<span class="icon calendar" aria-hidden="true"><i class="fa-solid fa-calendar"></i>
						</span>
						Audiences
						<span class="category__btn transparent-btn" title="Open list">
							<span class="sr-only">Open list</span>
							<span class="icon arrow-down" aria-hidden="true"></span>
						</span>
					</a>
					<ul class="cat-sub-menu">
						<li>
							<a href="#">Toutes les audiences</a>
						</li>

					</ul>
				</li>
				<li>
					<a class="show-cat-btn" href="##">
						<span class="icon file-text" aria-hidden="true"><i class="fa-solid fa-file-lines"></i>
						</span>Decisions
						<span class="category__btn transparent-btn" title="Open list">
							<span class="sr-only">Open list</span>
							<span class="icon arrow-down" aria-hidden="true"></span>
						</span>
					</a>
					<ul class="cat-sub-menu">
						<li>
							<a href="#">Toutes les decisions</a>
						</li>

					</ul>
				</li>
				<li>
					<a class="show-cat-btn" href="##">
						<span class="icon users" aria-hidden="true">
							<i class="fa-solid fa-users"></i>
						</span>Utilisateurs
						<span class="category__btn transparent-btn" title="Open list">
							<span class="sr-only">Open list</span>
							<span class="icon arrow-down" aria-hidden="true"></span>
						</span>
					</a>
					<ul class="cat-sub-menu">
						<li>
							<a href="#">Tous les utilisateurs</a>
						</li>
						<li>
							<a href="#">Ajouter un utilisateur</a>
						</li>
					</ul>
				</li>
				<li>
					<a class="show-cat-btn" href="##">
						<span class="icon building" aria-hidden="true">
							<i class="fa-solid fa-building"></i>
						</span>Parquets
						<span class="category__btn transparent-btn" title="Open list">
							<span class="sr-only">Open list</span>
							<span class="icon arrow-down" aria-hidden="true"></span>
						</span>
					</a>
					<ul class="cat-sub-menu">
						<li>
							<a href="#">Tous les parquets</a>
						</li>

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