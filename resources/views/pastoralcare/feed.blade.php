<?php
	use App\Next\Models\Homework;
	use App\Next\Models\Lesson;
	use App\Next\Models\User;
	use App\Next\Data\HomeworkSource;
	use App\Next\Layout\Colours;
	use Carbon\Carbon;

	$now = Carbon::now()
?>

<?php $feed = User::active()->pastoralcare_feed(); ?>

@if (count($feed) == 0)
	<div class="feed-empty">
		You don't have any Pastoral Care items... somehow.
	</div>
@else
	@foreach($feed as $tile)
		@include('pastoralcare/tile', ['pastoralcare' => $tile])
	@endforeach
@endif
