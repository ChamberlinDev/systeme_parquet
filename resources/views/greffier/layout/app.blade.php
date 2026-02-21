<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title> Dashboard</title>
	<!-- Favicon -->
	<link rel="shortcut icon" href="{{ asset('./img/svg/logo.svg') }}" type="image/x-icon">
	<!-- Custom styles -->
	<link rel="stylesheet" href="{{ asset('./css/style.min.css') }}">
</head>

<body>
	<div class="layer"></div>
	<!-- ! Body -->
	<a class="skip-link sr-only" href="#skip-target">Skip to content</a>
	<div class="page-flex">
		<!-- ! Sidebar -->
		@include('greffier.partials.sidebar')
		<div class="main-wrapper">
			<!-- ! Main nav -->
			@include('greffier.partials.header')
			<!-- ! Main -->
			 <div class="main-content">
				@yield('content')
			</div>
			<!-- ! Footer -->
			@include('admin.partials.footer')	
		</div>
	</div>
	<!-- Chart library -->
	<script src="{{ asset('./plugins/chart.min.js') }}"></script>
	<!-- Icons library -->
	<script src="{{ asset('plugins/feather.min.js') }}"></script>
	<!-- Custom scripts -->
	<script src="{{ asset('js/script.js') }}"></script>
</body>

</html>