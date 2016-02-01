@extends('layout')

@section('body-attr')
	class="setup"
@endsection

@section('title')
	404 Error | Keystone Next
@endsection

@section('body')

	<div class="setup-wrapper">
		<div class="next-logo"></div>
		<div class="setup-title">404 Error</div>
		<div class="locked-details">The path '{{$_SERVER['REQUEST_URI']}}' was not found.</div>
	</div>

@endsection