@extends('layouts.default')

@section('content')
	<main class="row container">

		<script>
			// Tracks changes on checkboxes
			function addToList( id ){

				var currentText = $('input[name=allSubscriptions]').val();
				if( currentText.search(new RegExp(" ?" + id + " ?")) >= 0 ){
					// If the number's already in the string, remove it
					
					$('input[name=allSubscriptions]').val(
						currentText.replace( new RegExp(" ?" + id + " ?"), " ")
					);
				} else {
					$('input[name=allSubscriptions]').val( currentText + " " + id );
				}
			}
		</script>
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