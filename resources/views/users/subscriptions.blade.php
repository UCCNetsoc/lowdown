@extends('layouts.default')

@section('content')
	<main class="row container">

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
	</main>

@stop