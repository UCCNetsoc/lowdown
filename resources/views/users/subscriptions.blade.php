@extends('layouts.default')

@section('content')
	<main class="row container">
		{!! Form::open( array('route' => 'subscriptions/add', 'method' => 'post', 'class' => 'row col s12') ) 		!!}
		@foreach($societies as $society)
			<p>
				<input 
					type="checkbox" id="{{ $society->id }}" name="{{ $society->id }}" 

					@if( $society->checked )
						checked="checked"
					@endif
				/>
				<label for="{{ $society->id }}">{{ $society->name }}</label>
			</p>
		@endforeach

		<button class="btn waves-effect waves-light" type="submit" name="action">Login
			<i class="mdi-content-send right"></i>
		</button>
		{!! Form::close() !!}
	</main>

@stop