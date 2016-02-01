<div class="tile lesson {{{ $lesson->subject == 'Free' ? 'free' : '' }}}" data-id="{{{ $lesson->id }}}">
	<div class="tile-content">
		<div class="title">{{{ $lesson->subject }}}@if (strlen($lesson->location) > 0) <div class="body"> Room {{{ $lesson->location }}}</div>@endif</div>
	</div>

	<div class="bubbles">
		<?php
			use App\Next\Layout\Colours;
		?>
		<?php $due_delta = $lesson->start->timestamp - time(); ?>
		<div class="bubble due {{ $due_delta < 0 ? 'urgent' : '' }}" style="background-color: {{{ Colours::due_hsl($due_delta) }}};">{{{ $lesson->due_time() }}}</div>
		@if($lesson->start->diffInMinutes($lesson->end) > 60)
			<div class="bubble" style="background-color: hsl(190, 70%, 50%);">x2</div>
		@endif
	</div>
</div>