@extends('layout')

@section('body-attr')
	class="{{{ $name }}}" data-page="{{{ $name }}}"
@endsection

@section('title')
	Keystone Next
@endsection

@section('body')
	<div class="bubble-menu">
		<div class="bubble-menu-item bubble-sportszone" title="SportsZone" data-id="sportszone">
			<div class="bubble-name">SZ</div>
		</div>

		<div class="bubble-menu-item bubble-timetable" title="Timetable" data-id="timetable">
			<div class="bubble-name">TT
		</div>

		</div>
		<div class="bubble-menu-item bubble-homework" title="Homework" data-id="homework">
			<div class="bubble-name">HW
		</div>

		</div>
		<div class="bubble-menu-item bubble-notifications" title="Notifications" data-id="notifications">
			<div class="bubble-name">NT
		</div>

		</div>
		<div class="bubble-menu-item bubble-pastoralcare" title="Pastoral Care" data-id="pastoralcare">
			<div class="bubble-name">PC
		</div>

		</div>
		<div class="bubble-menu-item bubble-options" title="Options" data-id="options">
			<div class="bubble-name">OP
		</div>

		</div>
		</div>

	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	{{-- <script src="jquery-2.1.3.min.js"></script> --}}
	<script src="swipeDelete.jquery.js"></script>
	<script src="script.js"></script>
	<script src="bubblemenu-basic.js"></script>
	<div class="tiles-wrapper">
		<div class="tiles">
			@include($name . '/feed')
		</div>
	</div>
@endsection
