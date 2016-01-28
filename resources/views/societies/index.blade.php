@extends('layouts.default')

@section('body-class') socs-list @endsection

@section('content')
	<main class="row container">
			<?php $lastLetter = ''; ?>
			<div class="row">
				@foreach(\App\Society::orderBy('name')->get() as $society)
					<?php $currentLetter = substr($society->name, 0, 1); ?>
					
					@if( $currentLetter != $lastLetter )
					</div>
					<div class="row card white">
						<h3 class="col s12"> {{ $currentLetter }} </h3>
					@endif
					<p class="col m4 s12"><a href="{{ URL::to('/socs/' . $society->facebook_ref) }}" class="waves-effect waves-light btn">{{ $society->name }} Society</a></p>
					<?php $lastLetter = $currentLetter; ?>	
				@endforeach
			</div>
	</main>
@stop