<div class="tile sportszone">
	<div class="tile-content">
		<div class="sports-item main">
			<div class="title">{{{ $match->team_name }}}</div>
			<div class="body">{{{ $match->date->format('g:i a \o\n \t\h\e jS \o\f F') }}}</div>
		</div>
		<div class="sports-item">
			<div class="subtitle">Match</div>
			<div class="body">Against {{{ $match->opponent_name }}} at {{{ $match->venue }}}</div>
		</div>
		<br>
		<div class="sports-item">
			<div class="subtitle">Players</div>
			<?php
				use App\Next\Models\User;
				$active_id = User::active()->user_id;
			?>
			<div class="people">
				@foreach ($match->team_members() as $person)
					<div class="person hover {{{ $person->person_id == $active_id ? 'person-self' : '' }}}" style="background-image: url({{ $person->profile_image_path() }});" data-name="{{{ $person->name }}}"></div>
				@endforeach
			</div>
		</div>
		<div class="sports-item">
			<div class="subtitle">Staff</div>
			<div class="people">
				@foreach ($match->staff_members() as $person)
					<div class="person hover {{{ $person->person_id == $active_id ? 'person-self' : '' }}}" style="background-image: url({{ $person->profile_image_path() }});" data-name="{{{ $person->name }}}"></div>
				@endforeach
			</div>
		</div>
	</div>

	<div class="map-wrapper">
		<iframe class="map scroll-off" height="250" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/view?key=AIzaSyBF6s-bWa4B5bHU2msD1ZBKTuzO0djxL48&center={{ $match->location }}&zoom=15"></iframe>
	</div>
</div>