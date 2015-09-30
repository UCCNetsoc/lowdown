<!DOCTYPE html>
<html>
<head>
	<title>@yield('title', env('SITE_TITLE'))</title>
	<meta charset="utf-8">
	<meta name="description" content="@yield('description')" />
	<meta name="author" content="NETSOC" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<link rel="shortcut icon" href="{{ URL::to('/') }}/images/favicon.png">

    <meta property="og:title" content="@yield('title', env('SITE_TITLE'))" />
    <meta property="og:image" content="{{ URL::to('/images/ogimage.png') }}" />
	
	<link rel="stylesheet" type="text/css" href="{{ URL::to('/') }}/css/font-awesome.min.css">
	<link rel="stylesheet" href="{{ URL::to('/') }}/css/normalize.css">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.1/css/materialize.min.css">
	<link rel="stylesheet" type="text/css" href="{{ URL::to('/') }}/css/app.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	@yield('extra-css')
	@yield('extra-js')


	@yield('extra-head')
</head>
<body @if( Route::current()->getPath() == '/' ) class="homepage" @endif>
    @yield('before-page')
    <header>
    	<nav>
			<div class="nav-wrapper container">
			  <a href="{{ URL::to('/home') }}" class="brand-logo">
			  	<figure>
					@if( Route::current()->getPath() == '/' )
						{{-- If is the front page, use the alternate logo --}}
						<img src="{{ App\Setting::where('name', 'logo_alt')->first()->setting }}" alt="{{ env( 'SITE_TITLE' ) }}" class="logo">
					@else
						{{-- Otherwise, use the normal one --}}
						<img src="{{ App\Setting::where('name', 'logo')->first()->setting }}" alt="{{ env( 'SITE_TITLE' ) }}" class="logo">
					@endif
					<figcaption class="sr-only">
						<h1> {{ env( 'SITE_TITLE' ) }}</h1>
					</figcaption>
				</figure>
			  </a>
			  <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
			  <ul id="dropdown1" class="dropdown-content">
		  		<li>
		  			<a href="{{ URL::route('day', ['day' => 'monday']) }}">
		  				Monday
		  			</a>
		  		</li>
		  		<li>
		  			<a href="{{ URL::route('day', ['day' => 'tuesday']) }}">
		  				Tuesday
		  			</a>
		  		</li>
		  		<li>
		  			<a href="{{ URL::route('day', ['day' => 'wednesday']) }}">
		  				Wednesday
		  			</a>
		  		</li>
		  		<li>
		  			<a href="{{ URL::route('day', ['day' => 'thursday']) }}">
		  				Thursday
		  			</a>
		  		</li>
		  		<li>
		  			<a href="{{ URL::route('day', ['day' => 'friday']) }}">
		  				Friday
		  			</a>
		  		</li>
				</ul>
			  <ul class="right hide-on-med-and-down">
			  	<li><a class="dropdown-button black-text" href="#!" data-activates="dropdown1">Events<i class="material-icons right">arrow_drop_down</i></a></li>
			  	@if( Auth::check( ) )
			  		<li class="login"><a href="{{ URL::route('subscriptions') }}" class="btn waves-effect waves-light">Subscriptions</a></li>
			  	@else 
					<li class="login">
						<a class="btn waves-effect waves-light modal-trigger" href="#login-modal">Login</a>
					</li>
					<li class="register">
						<a href="{{ URL::route('register') }}" class="btn">Signup</a>
					</li>
			  	@endif
			  </ul>

			  <ul class="side-nav" id="mobile-demo">

			  	@if( Auth::check( ) )
			  		<li><a href="{{ URL::to( 'home' ) }}" class="black-text">Home</a></li>
			  		<li><a href="{{ URL::route('subscriptions') }}" class="black-text">Subscriptions</a></li>
			  	@else 
					<li class="login"><a class="waves-effect waves-light modal-trigger black-text" href="#login-modal">Login</a></li>
					<li><a href="{{ URL::route('register') }}" class="black-text">Register</a></li>
			  	@endif
			 
			  </ul>
			</div>
		</nav>
	</header>