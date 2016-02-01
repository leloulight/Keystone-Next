<?php 

	namespace App\Console\Commands;

	use Illuminate\Console\Command;

	class QueueRun extends Command {

		/**
		 * The console command name.
		 *
		 * @var string
		 */
		protected $name = 'next:queuerun';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Update the notifications listing.';

		private function start_queue ()
		{
		    $command = 'php artisan queue:listen --timeout=240 > /dev/null & echo $!';
		    $number = exec($command);
		    file_put_contents('/home/oeed/subd/next/resources/queue.pid', $number);
		}

		
		public function handle()
		{
			if (file_exists('/home/oeed/subd/next/resources/queue.pid')) {
			    $pid = file_get_contents('/home/oeed/subd/next/resources/queue.pid');
			    $result = exec('ps | grep ' . $pid);
			    if ($result == '') {
			        $this->start_queue();
			    }
			} else {
			    $this->start_queue();
			}


		}

	}

?>