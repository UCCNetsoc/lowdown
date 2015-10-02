@extends('layouts.default')

@section('content')

<div class="fourohfour col row s12">
	<h1 class="center-align">404</h1>
	<h2 class="center-align">This page doesn't exist.</h2>
	@if( env('ISSUE_TRACKER') )
		<p class="center-align">If you'd like to report a bug or issue, have a look at <a href="{{ env('ISSUE_TRACKER') }}">our issue tracker</a></p>
	@endif
</div>

@stop