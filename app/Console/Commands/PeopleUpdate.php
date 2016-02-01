<?php 

	namespace App\Console\Commands;

	use App\Next\LoggingCommand;
	use App\Next\Data\PeopleSource;

	class PeopleUpdate extends LoggingCommand {

		/**
		 * The console command name.
		 *
		 * @var string
		 */
		protected $name = 'next:peopleupdate';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Update the people in the database.';

		/**
		 * Execute the console command.
		 *
		 * @return mixed
		 */
		public function handle()
		{
			$this->log_wake();
			$errors = 0;
			try {
				PeopleSource::update($this);
			} catch (\Exception $e) {
				$errors ++;
				$this->comment('Exception');
				$this->error($e);
				$this->log_section_end('Failed.', true);
			}

			$this->info("Completed with $errors errors.");

			if ($errors != 0)
				$this->dispatch("People");
		}

	}

?>