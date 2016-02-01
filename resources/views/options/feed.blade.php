<?php
	use App\Next\Models\Homework;
	use App\Next\Models\Lesson;
	use App\Next\Models\User;
	use App\Next\Data\HomeworkSource;
	use App\Next\Layout\Colours;
	use Carbon\Carbon;

	$now = Carbon::now()
?>

<div class="option-flex">
	<div class="option">
		<div class="heading">Profile <span class="detail">Who you are</span></div>
		<div class="option-flex">
			<?php $person = User::active()->person; ?>
			<div class="profile-image" style="background-image: url({{ $person->profile_image_path() }});"></div>
			<div class="option-details">
				<div class="option-maintext">{{{ $person->name }}}</div>
				<?php
					if($person->job_title == 'Student')
						$str = 'Year ' . $person->year_level . ' Student' ;
					else
						$str = $person->job_title;
				?>
				<div class="option-subtext">{{{ $str }}}</div>
			</div>

			<a href="/auth/logout">
				<div class="button option-button-small logout-button">Logout</div>
			</a>
		</div>
	</div>
</div>