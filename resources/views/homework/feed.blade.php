<?php
	use App\Next\Models\Homework;
	use App\Next\Models\Lesson;
	use App\Next\Models\User;
	use App\Next\Data\HomeworkSource;
	use App\Next\Layout\Colours;
	use Carbon\Carbon;

	$now = Carbon::now()
?>

<div class="button-wrapper">
	<div class="button toggle-complete-button">Show Complete</div>
</div>

@foreach(User::active()->homework_feed() as $group)
	<?php
		$all_complete = true;

		foreach ($group['items'] as $homework) {
			if (!$homework->pivot->complete) {// || $homework->pivot->updated_at->diffInMinutes() <= 15) {
				$all_complete = false;
				break;
			}
		}
	?>
	<div class="heading {{{ $all_complete ? 'complete complete-invisible' : '' }}}">{{{ $group['heading'] }}}</div>
	@foreach($group['items'] as $tile)
		@include('homework/tile', ['homework' => $tile])
	@endforeach
@endforeach
<script src="homework.js"></script>
