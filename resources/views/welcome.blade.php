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
@stop

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
@if(count($first_six) == 6)
	<section class="event-cards z-depth-2">
		<div class="container">
		<div class="row ">
			@foreach($first_six as $event)
				<div class="col s12 m6 l4">
					<div class="card">
						@if( $event->image )
							<div class="card-image">
								<a href="https://www.facebook.com/events/{{$event->facebook_id}}">
									<img src="{{$event->image}}">
								</a>
							</div>
						@endif
						<div class="card-content">
							<h4>{{$event->title}}</h5>
							<h5>{{$event->society()->first()->name}} Society</h5>
							<p>{{date('H:i, l j F', strtotime($event->time))}}@if($event->location), <strong>{{$event->location}}</strong>@endif</p>
						</div>
						<div class="card-action">
						  <a href="https://www.facebook.com/events/{{$event->facebook_id}}">
						  	Facebook Event
						  </a>
						</div>
					</div>
				</div>
			@endforeach
		</div>
		</div>
	</section>
@endif

	<section id="description" class="row z-depth-2">
		<div class="container">
			<h1>What is Lowdown?</h1>
			<p class="flow-text">It's everything you love in one place; your favourite societies events organised and collected online, sent out to you in a personalised newsletter every week. We're helping societies find their audience, and students find new interests.</p>
			<div class="what-is-lowdown row">
				<div class="col s12 m4">
				  <div class="icon-block">
					<h2 class="center"><i class="material-icons">hearing</i></h2>
					<h5 class="center">Always Up To Date</h5>

					<p class="light">Lowdown sources all of its information direct from societies' Facebook pages, so you can be assured if something's happening on campus, we'll hear about it.</p>
				  </div>
				</div>

				<div class="col s12 m4">
				  <div class="icon-block">
					<h2 class="center"><i class="material-icons">email</i></h2>
					<h5 class="center">Never Miss A Beat</h5>

					<p class="light">Sign up to Lowdown and get weekly emails about your societies' upcoming events every Sunday. We'll even let you get your favourite society's events on your phone calendar.</p>
				  </div>
				</div>

				<div class="col s12 m4">
				  <div class="icon-block">
					<h2 class="center">♥</h2>
					<h5 class="center">Everything You Love</h5>

					<p class="light">Pick and choose the societies you want to follow. Get more of what you care about and what interests you. Discover new societies and get involved with something different.</p>
				  </div>
				</div>
			</div>

			<div class="center">
				<a href="#call-to-action" class="btn-large waves-effect waves-light teal lighten-1"><i class="material-icons left">thumb_up</i> Sign Me Up!</a>
			</div>
		</div>
	</section>

@if(count($next_six) == 6)
	<section class="event-cards z-depth-2">
		<div class="container">
			<div class="row ">
				@foreach($next_six as $event)
					<div class="col s12 m6 l4">
						<div class="card">
							@if( $event->image )
								<div class="card-image">
									<a href="https://www.facebook.com/events/{{$event->facebook_id}}">
										<img src="{{$event->image}}">
									</a>
								</div>
							@endif
							<div class="card-content">
								<h4>{{$event->title}}</h5>
								<h5>{{$event->society()->first()->name}} Society</h5>
								<p>{{date('H:i, l j F', strtotime($event->time))}}@if($event->location), <strong>{{$event->location}}</strong>@endif</p>
							</div>
							<div class="card-action">
							  <a href="https://www.facebook.com/events/{{$event->facebook_id}}">
							  	Facebook Event
							  </a>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</section>
@endif

	<section id="call-to-action" class="row">
		<div class="container white-text center">
			<h2> Signup </h2>
			<div class="form-wrapper">
				@include('forms.register')
			</div>
		</div>
	</section>
</main>
@endsection

@section('after-page')
<footer class="page-footer">
  <div class="footer-copyright">
    <div class="container">
    © {{date('Y')}} UCC Netsoc
    <a class="grey-text text-lighten-4 right" href="https://github.com/UCCNetworkingSociety/lowdown/blob/master/LICENSE">MIT Licence</a>
    </div>
  </div>
</footer>
@endsection