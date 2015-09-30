@extends('layouts.default')

{{-- 
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
--}}

@section('content')

	<main class="row container">
		<section class="card-panel register-card white col s12 l6 offset-l3">
			<h3 class="center-align"> Register </h3>
			@include('forms.register')

			<br /> 
		</section>

	</main>
{{-- </div> --}}
@endsection
