@extends('layouts.default')

@section('content')
	<main class="row container">
			@foreach(\App\Society::orderBy('name')->get() as $society)
				<p class="col m4 s12"><a href="{{ URL::to('/socs/' . $society->facebook_ref) }}">{{ $society->name }} Society</a></p>
			@endforeach
	</main>
@stop