@extends('layouts.default')

@section('before-page')
<header class="parallax-container welcome-page-parallax z-depth-2">
	<div class="parallax">
		<video width="100%" autoplay loop style="max-width:100%">
			<source src="{{URL::to('/')}}/images/video.webm" type="video/webm">
			<source src="{{URL::to('/')}}/images/video.mp4" type="video/mp4">
			<img src="{{URL::to('/')}}/images/video.jpg">
		</video>
	</div>
@endsection

@section('content')
	<div class="valign-wrapper container row welcome-page-hero">
		<div class="col s12 valign">
			<hr/>
			<div class="s12 center-align">
				<h1>Lowdown.</h1>
				<h2>The complete guide to UCC Societies.</h2>
				<a href="#description" class="btn-large waves-effect waves-light teal lighten-1"><i class="material-icons left">chat</i> Learn More</a>
			</div>
			<hr/>
		</div>
	</div>
<!-- End Parallax container -->
</header>

<main>

@if(count($randomevents) >= 3)
	<div class="row z-depth-2" id="events">
		<div class="parallax-container">
			<div class="parallax"><img src="{{ URL::to('/') . '/images/red-geometric-background.png'}}"></div>
			<div class="col s12 m12 l10 offset-l1">
				<h3>Upcoming Events</h3>
				@foreach( $randomevents as $event )
					<div class="col s12 m4">
						<div class="card">
							<div class="card-image">
								<img src="{{$event->image}}">
								<span class="card-title">{{$event->title}}</span>
							</div>
							<div class="card-content">
								<p><em>{{date('H:i, l j F', strtotime($event->time))}}@if($event->location), <strong>{{$event->location}}</strong>@endif</em></p>
								<p>{{ str_limit($event->description, 250) }}</p>
							</div>
							<div class="card-action">
								<a href="https://www.facebook.com/events/{{$event->facebook_id}}" class="red-text text-lighten-2">View Event &rarr;</a>
								<a href="{{ URL::route('soc', ['id' => $event->society->id]) }}" class="red-text text-lighten-2">{{$event->society->name}} Society &rarr;</a>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</div>
@endif

@if( count($events) > 1 )
	<div class="row remove-col-padding" id="photos">
		@foreach($events as $event )
			<div class="col s6 m3">
				<div class="card hoverable">
					<a href="https://www.facebook.com/events/{{$event->facebook_id}}">
						<div class="card-image center-cropped" style="background-image: url('{{$event->image}}');">
							<img src="{{$event->image}}" />
						</div>
					</a>
				</div>
			</div>
		@endforeach
	</div>
@endif

	<section id="call-to-action" class="row home-background">
		<div class="parallax-container">
			<div class="parallax"><img src="{{ URL::to('/') . '/images/red-geometric-background.png'}}"></div>
			<div class="container center">
				<h2> Signup For Weekly Summaries</h2>
				<div class="form-wrapper">
					@include('forms.register')
				</div>
			</div>
		</div>
	</section>
</main>
@endsection

@section('after-page')
<footer class="page-footer home-footer">
  <div class="footer-copyright">
	<div class="container">
	© {{date('Y')}} UCC Netsoc
	<a class="grey-text text-lighten-4 right" href="https://github.com/UCCNetworkingSociety/lowdown/blob/master/LICENSE">MIT Licence</a>
	</div>
  </div>
</footer>
@endsection