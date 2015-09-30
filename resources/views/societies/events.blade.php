@extends('layouts.default')


@section('content')
<main class="container events">
	<hr />
	<h1>{{$society_name}}</h1>
	<hr />

	<p class="col s12 prefix-m1 m3 right"><a class="calendar-button btn right" href='{{URL::to("/socs/$society_ref/calendar")}}' ><i class="material-icons left">today</i> Get Calendar</a></p>

	<div style="width:100%;clear:both"></div>

	<div class="row">
		@foreach($events as $event)
			<div class="col s12 m6 l4">
				<div class="card hoverable">
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

						@if($event->description)
							<p>{{$event->description}}</p>
						@endif
					</div>
					<div class="card-action">
					  <a href="https://www.facebook.com/events/{{$event->facebook_id}}">
					  	Facebook Event
					  </a>
					 <a href="{{URL::to('/event/' . $event->id . '/calendar')}}" class="calendar-button">
					  	Add To Calendar
					 </a>

					</div>
				</div>
			</div>
		@endforeach
	</div>
</main>
@endsection