<?php
	use App\Next\Models\Homework;
	use App\Next\Models\Lesson;
	use App\Next\Models\User;
	use App\Next\Data\HomeworkSource;
	use App\Next\Layout\Colours;
	use Carbon\Carbon;

	$now = Carbon::now()
?>

<?php $feed = User::active()->sportszone_feed(); ?>


@if (count($feed) == 0)
	<div class="feed-empty">
		You don't have any recent or upcoming matches.
	</div>
@else
	@foreach($feed as $group)
		<div class="heading">{{{ $group['heading'] }}}</div>
		@foreach($group['items'] as $tile)
			@include('sportszone/tile', ['match' => $tile])
		@endforeach
	@endforeach
@endif

<script src="sportszone.js"></script>
