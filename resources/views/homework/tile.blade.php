{{-- ($homework->pivot->updated_at->diffInMinutes() <= 15 ? 'complete complete-recent' : --}} 
<div class="tile homework {{{ $homework->pivot->complete ? 'complete complete-invisible' : '' }}}" data-id="{{{ $homework->homework_id }}}">
	<div class="tile-content">
		<div class="title">{{{ $homework->title }}}</div>
		@if ($homework->description)
			<div class="body">{!! nl2br(e($homework->description)) !!}</div>
		@endif
	</div>

	<div class="bubbles">
		<?php
			use App\Next\Layout\Colours;
		?>
		<?php $due_delta = $homework->end->timestamp - time(); ?>
		<div class="bubble due {{ $due_delta < 0 ? 'urgent' : '' }}" style="background-color: {{{ Colours::due_hsl($due_delta) }}};">{{{ $homework->due_time() }}}</div>
		@if ($homework->subject)
			<div class="bubble subject" title="{{{ $homework->subject }}}"style="background-color: {{ Colours::subject_hsl($homework->subject) }};">{{{ substr($homework->subject, 0, 3) }}}</div>
		@endif
	</div>
</div>