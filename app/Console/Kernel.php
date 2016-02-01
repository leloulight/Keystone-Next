<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\HomeworkUpdate',
		'App\Console\Commands\TimetableUpdate',
		'App\Console\Commands\NotificationsUpdate',
		'App\Console\Commands\SportsZoneUpdate',
		'App\Console\Commands\PastoralCareUpdate',
		'App\Console\Commands\PeopleUpdate',
		'App\Console\Commands\QueueRun',
	];

	protected function log($name) {
		$path = "/home/oeed/subd/next/logs/$name/$name-" . date('Y-m-d-H:i') . '.log';
		// touch($path);
		return $path;
	}

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		// $schedule->command('next:queuerun')
		// 		 ->cron('* * * * *');

		$schedule->command('next:homeworkupdate')
			     // ->withoutOverlapping()
				 ->everyTenMinutes();
		$schedule->command('next:notificationsupdate')
			     ->withoutOverlapping()
				 ->cron('*/15 * * * 1,2,3,4,5');
		$schedule->command('next:pastoralcareupdate')
			     ->withoutOverlapping()
				 ->everyThirtyMinutes()
				 ->weekdays();
		$schedule->command('next:sportszoneupdate')
			     ->withoutOverlapping()
				 ->everyThirtyMinutes();
		$schedule->command('next:timetableupdate')
			     ->withoutOverlapping()
			     ->weekly()->mondays()->at('1:00');
		$schedule->command('next:peopleupdate')
			     ->withoutOverlapping()
			     ->weekly()->sundays()->at('3:00');
	}

}
