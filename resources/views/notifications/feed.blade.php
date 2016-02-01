<?php
	use App\Next\Models\Homework;
	use App\Next\Models\Lesson;
	use App\Next\Models\User;
	use App\Next\Data\HomeworkSource;
	use App\Next\Layout\Colours;
	use Carbon\Carbon;

	$now = Carbon::now()
?>

<?php $feed = User::active()->notifications_feed(); ?>

@if (count($feed) == 0)
	<div class="feed-empty">
		You don't have any unread notifications.
	</div>
@else
	<div class="button-wrapper">
		<div class="button mark-all-read-button">Mark All As Read</div>
	</div>

	@foreach($feed as $tile)
		@include('notifications/tile', ['notification' => $tile])
	@endforeach
@endif
<script src="notifications.js"></script>
