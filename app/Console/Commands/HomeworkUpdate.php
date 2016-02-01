<?php 

	namespace App\Console\Commands;

	use App\Next\LoggingCommand;
	use App\Next\Data\HomeworkSource;
	use App\Next\Models\User;

	class HomeworkUpdate extends LoggingCommand {

		/**
		 * The console command name.
		 *
		 * @var string
		 */
		protected $name = 'next:homeworkupdate';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Update the homework listing.';

		/**
		 * Execute the console command.
		 *
		 * @return mixed
		 */
		public function handle()
		{	
			$this->log_wake();
			$errors = 0;
			foreach (User::all() as $index => $user) {
				$this->log_section('User ' . $user->user_id);
				try {
					HomeworkSource::update($user);
					$this->log_section_end('Completed successfully.');
				} catch (\Exception $e) {
					$errors ++;
					$this->comment('Exception');
					$this->error($e);
					$this->log_section_end('Failed.', true);
				}
			}

			$this->info("Completed with $errors errors.");

			if ($errors != 0)
				$this->dispatch("Homework");
		}

	}

?>