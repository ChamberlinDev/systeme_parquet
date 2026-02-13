<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">
<div class="logo-header">
	<a href="/accueil_greffier" class="logo">
		SGSDP
	</a>
	<button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<button class="topbar-toggler more"><i class="la la-ellipsis-v"></i></button>
</div>
<nav class="navbar navbar-header navbar-expand-lg">
	<div class="container-fluid">


		<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">

			<!-- <li class="nav-item dropdown hidden-caret">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="la la-bell"></i>
								<span class="notification">1</span>
							</a>
							<ul class="dropdown-menu notif-box" aria-labelledby="navbarDropdown">
								<li>
									<div class="dropdown-title">You have a new notification</div>
								</li>
								
								
							</ul>
						</li> -->
			<li class="nav-item dropdown">
				<a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
					<i class="la la-user-circle align-middle mr-1" style="font-size: 26px;"></i>
					<span>{{ auth()->user()->name }}</span>
				</a>
				<ul class="dropdown-menu dropdown-user">
					<li>
						<div class="user-box">
							<div class="u-img">
								<i class="la la-user-circle align-middle mr-1" style="font-size: 26px;"></i>

							</div>
							<div class="u-text">
								<h4>{{auth()->user()->name}}</h4>
								<p class="text-muted">{{auth()->user()->email}}</p><a href="{{ route('profil.voir') }}" class="btn btn-rounded btn-danger btn-sm">Voir profil</a>
							</div>
						</div>
					</li>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="{{ route('profil.voir') }}"><i class="ti-user"></i>Profil</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="{{route('logout')}}"><i class="fa fa-power-off"></i> Deconnexion</a>
				</ul>
				<!-- /.dropdown-user -->
			</li>
		</ul>
	</div>
</nav>
</div>