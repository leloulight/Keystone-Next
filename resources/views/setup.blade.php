@extends('layout')

@section('body-attr')
	class="setup"
@endsection

@section('title')
	Setup | Keystone Next
@endsection

@section('body')

	<div class="setup-wrapper">
		<div class="next-logo"></div>

		<div class="setup-title">Setting up Next for you</div>
		<div class="setup-time">About 1 to 5 minutes remaining</div>
		<div class="setup-status">Waiting in queue...</div>
	</div>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src="setup.js"></script>
@endsection
