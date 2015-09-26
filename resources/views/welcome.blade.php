@extends('layouts.default')

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

@section('content')
    <main class="valign-wrapper row welcome-page-hero">
    	<div class="col s12 valign">
    		<hr/>
	        <div class="s12 center-align">
	            <h1>Lowdown.</h1>
	            <h2>It's what's goin' on.</h2>
	        </div>
	        <hr/>
	    </div>
    </main>
<!-- End Parallax container -->
</div>
@stop

@section('after-page')

@stop