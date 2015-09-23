@extends('layouts.default')

@section('content')
	<main class="row container">

		{!! Form::open( array('route' => 'subscriptions/add', 'method' => 'post', 'class' => 'row col s12') ) 		!!}
		
		<div class="col m6 s12">
			@foreach($societies as $society)
				@if( $society->id % ($numberOfSocieties / 2) )
					</div>
					<div class="col m6 s12">
				@endif
				<p>
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
		</div>
		
		<input type="hidden" name="allSubscriptions" value=""/>
		<button class="btn waves-effect waves-light" type="submit" name="action">Update
			<i class="mdi-content-send right"></i>
		</button>
		{!! Form::close() !!}
	</main>

@stop