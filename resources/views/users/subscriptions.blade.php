@extends('layouts.default')

@section('content')
	<main class="row container">
		<p class="col s12 prefix-m1 m3 right"><a class="calendar-button btn right" href='{{ URL::to("/calendar/" . Crypt::encrypt(Auth::user()->id) ) }}' ><i class="material-icons left">today</i> Get Calendar</a></p>
		<p class="col s8">Choose which societies you'd like to hear from. 
			<a class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="The idea of the lowdown is to learn about new society events, we want to encourage that more than anything.">
				Why are all the societies ticked by default?
			</a>
		</p>

		@if(Auth::user()->unsubscribed_email == 'yes')
			<p class="col s12">You're not subscribed to receive emails from us. <a href='{{ URL::to("/emails/resubscribe/" . Crypt::encrypt(Auth::user()->id) ) }}'>Subscribe to emails.</p>
		@endif


		{!! Form::open( array('route' => 'subscriptions/add', 'method' => 'post', 'class' => 'row col s12') ) 		!!}
		
			@foreach($societies as $society)
				
				<p class="col m4 s12">
					<input 
						type="checkbox" id="{{ $society->id }}" name="{{ $society->id }}" 

						@if( $society->checked )
							checked="checked"
						@endif

						onchange="addToList( {{ $society->id }} )"
					/>
					<label for="{{ $society->id }}">{{ $society->name }} Society</label>
				</p>
			@endforeach
		
		<input type="hidden" name="allSubscriptions" value=""/>
		<div class="row">
			<button class="btn waves-effect waves-light" type="submit" name="action" id="update-subscription">Update Subscription
				<i class="mdi-content-send right"></i>
			</button>
		</div>
		{!! Form::close() !!}

		@if(Auth::user()->unsubscribed_email == 'no')
			<p class="col s12">You're subscribed to receive emails from us. If you'd rather just get events through the Calendar you can <a href='{{ URL::to("/emails/unsubscribe/" . Crypt::encrypt(Auth::user()->id) ) }}'>unsubscribe from emails.</p>
		@endif
	</main>

@stop