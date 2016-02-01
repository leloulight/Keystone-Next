<div class="tile notification" data-id="{{{ $notification->notification_id }}}">
	<div class="tile-content">
		<div class="title">{{{ $notification->title }}}</div>
		<div class="body">{!! nl2br($notification->description) !!}</div>
	</div>

	<div class="bubbles">
		<?php
			use App\Next\Layout\Colours;
		?>
		<?php $due_delta = time() - $notification->date->timestamp; ?>
		<div class="bubble due" style="background-color: {{{ Colours::due_hsl_inverse($due_delta) }}};">{{{ $notification->due_time() }}}</div>

		<?php
			$person = $notification->author;
		?>

		@if ($person)
			<div class="person person-bubble hover" style="background-image: url({{ $person->profile_image_path() }});" data-name="{{{ $person->name }}}"></div>
		@endif

		{{-- <div class="bubble subject" title="{{{ $notification->author }}}" style="background-color: {{ Colours::subject_hsl('{{{ $initials }}}') }};">{{{ $initials }}}</div> --}}
	</div>
</div>