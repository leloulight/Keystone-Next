<?php namespace App\Commands;

use App\Commands\Command;

use App\Next\Models\User;
use App\Next\Data\HomeworkSource;
use App\Next\Data\NotificationsSource;
use App\Next\Data\PastoralCareSource;
use App\Next\Data\SportsZoneSource;
use App\Next\Data\TimetableSource;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class PrepareUser extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

	public $user;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user)
	{
		$this->user = $user;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{

		if($this->user->is_queued) {
			$time_start = microtime(true) . "s\n";

			echo 'Preparing user: ' . $this->user->user_id . "\n";

			if($this->user->is_queued <= 2) {
				$this->user->is_queued = 2;
				$this->user->save();
				echo "Homework\n";
				HomeworkSource::update($this->user, true);
			}

			$time_end = microtime(true);
			echo 'Time: ' . ($time_end - $time_start) . "s\n";
			
			if($this->user->is_queued <= 3) {
				$this->user->is_queued = 3;
				$this->user->save();
				echo "Notifications\n";
				NotificationsSource::update($this->user);
			}

			$time_end = microtime(true);
			echo 'Time: ' . ($time_end - $time_start) . "s\n";
			
			if($this->user->is_queued <= 4) {
				$this->user->is_queued = 4;
				$this->user->save();
				echo "PastoralCare\n";
				PastoralCareSource::update($this->user);
			}

			$time_end = microtime(true);
			echo 'Time: ' . ($time_end - $time_start) . "s\n";
			
			if($this->user->is_queued <= 5) {
				$this->user->is_queued = 5;
				$this->user->save();
				echo "SportsZone\n";
				SportsZoneSource::update($this->user);
			}

			$time_end = microtime(true);
			echo 'Time: ' . ($time_end - $time_start) . "s\n";
			
			if($this->user->is_queued <= 6) {
				$this->user->is_queued = 6;
				$this->user->save();
				echo "Timetable\n";
				TimetableSource::update($this->user);
			}

			$time_end = microtime(true);
			echo 'Time: ' . ($time_end - $time_start) . "s\n";
			
			$this->user->is_queued = 0;
			$this->user->save();


			$time_end = microtime(true);
			echo 'Time: ' . ($time_end - $time_start) . "s\n";

		}
		$this->user->save();
	}

}
