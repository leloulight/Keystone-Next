@extends('layout')

@section('body-attr')
	class="login"
@endsection

@section('title')
	Login | Keystone Next
@endsection

@section('body')

	<form role="form" method="POST" action="{{ url('/auth/login') }}">
		<div class="login-wrapper">
			<div class="next-logo"></div>

			@if (count($errors) > 0)
				@foreach ($errors->all() as $error)
					<div class="login-error">{{ $error }}</div>
				@endforeach
			@endif
		
			<input type="text" name="user_id" value="{{ old('user_id') }}" placeholder="Keystone Username">
			<input type="password" name="password" placeholder="Keystone Password">

			<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

			<input type="submit" value="Login"/>
		</div>
	</form>

@endsection
