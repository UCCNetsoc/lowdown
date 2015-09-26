@foreach ($errors->all() as $message)
    <li>{{ $message }}</li>
@endforeach

{!! Form::open([
	"route" => ['user/store'],
	"method" => "POST",
	'class' => 'row col s12'
]) !!}

<div class="row">
	<div class="input-field">
		{!! Form::label('name', 'First Name') !!}
		{!! Form::text('name', null, ["class" => "example"] ) !!}
	</div>
</div>

<div class="row">
	<div class="input-field">
		{!! Form::label('email', 'Email') !!}
		{!! Form::email('email', null, ["class" => "example"] ) !!}
	</div>
</div>

<div class="row">
	<div class="input-field">
		{!! Form::label('password', 'Password') !!}
		{!! Form::password('password', null, ["class" => "example"] ) !!}
	</div>
</div>
<div class="row">
	<div class="input-field">
		{!! Form::label('password_confirmation', 'Confirm Password') !!}
		{!! Form::password('password_confirmation', null, ["class" => "example"] ) !!}
	</div>
</div>
<button class="btn waves-effect waves-light" type="submit" name="action">Register
	<i class="mdi-content-send right"></i>
</button>
{!! Form::close() !!}