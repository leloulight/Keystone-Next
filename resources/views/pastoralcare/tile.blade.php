<div class="tile pastoralcare">
	<div class="tile-content">
		<div class="title">{{{ $pastoralcare->type }}} <span class="pastoralcare-category">{{{ $pastoralcare->category }}}</span></div>
		<div class="body">{!! nl2br(e($pastoralcare->description)) !!}</div>
	</div>

	<div class="bubbles">
		<?php
			use App\Next\Layout\Colours;
		?>
		<?php $due_delta = time() - $pastoralcare->date->timestamp; ?>
		<div class="bubble due" style="background-color: {{{ Colours::due_hsl_inverse($due_delta) }}};">{{{ $pastoralcare->due_time() }}}</div>

		<?php
			// $initials = '';
			// $words = explode(' ', $pastoralcare->author);
			// $initials .= $words[0][0];
			// if (count($words) > 1)
			// 	$initials .= end($words)[0];
		?>

		<?php
			$person = $pastoralcare->person();
		?>

		@if ($person)
			<div class="person person-bubble hover" style="background-image: url({{ $person->profile_image_path() }});" data-name="{{{ $person->name }}}"></div>
		@endif

		{{-- <div class="bubble subject" title="{{{ $pastoralcare->author }}}" style="background-color: {{ Colours::subject_hsl('{{{ $initials }}}') }};">{{{ $initials }}}</div> --}}
	</div>
</div>