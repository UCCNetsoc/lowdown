@extends('layouts.default')

@section('before-page')
<div class="parallax-container welcome-page-parallax">
	<div class="parallax">
		<video width="100%" autoplay loop style="max-width:100%">
			<source src="{{URL::to('/')}}/images/video.webm" type="video/webm">
			<source src="{{URL::to('/')}}/images/video.mp4" type="video/mp4">
			<img src="{{URL::to('/')}}/images/video.jpg">
		</video>
	</div>
@stop

@section('content')
    <main class="valign-wrapper row welcome-page-hero z-depth-2">
    	<div class="col s12 valign">
    		<hr/>
	        <div class="s12 center-align">
	            <h1>Lowdown.</h1>
	            <h2>It's what's goin' on.</h2>
	        </div>
	        <hr/>
	    </div>
    </main>
<!-- End Parallax container -->
</div>

	<section class="event-cards events  z-depth-2">
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
	</section>

	<section id="description" class="row z-depth-2">
		<div class="container">
			<h1>What is Lowdown?</h1>
			<p class="flow-text">Lorem ipsum Laboris dolore do exercitation occaecat tempor quis irure laboris dolore Excepteur laboris incididunt consectetur Duis cillum aute eu dolor non minim voluptate Excepteur incididunt reprehenderit Ut amet pariatur quis officia pariatur Excepteur tempor irure fugiat.</p>
		</div>
	</section>

	<section class="event-cards events z-depth-2">
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
	</section>

	<section id="call-to-action" class="row red-accent">
		<div class="col s12 m4 offset-m4 container card-panel">
			<h2 class="center-align"> Signup </h2>
			@include('forms.register')
		</div>
	</section>

@stop

@section('after-page')

@stop