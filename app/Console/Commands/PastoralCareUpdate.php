<?php 

	namespace App\Console\Commands;

	use App\Next\Data\PastoralCareSource;
	use App\Next\LoggingCommand;
	use App\Next\Models\User;

	class PastoralCareUpdate extends LoggingCommand {

		/**
		 * The console command name.
		 *
		 * @var string
		 */
		protected $name = 'next:pastoralcareupdate';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Update the pastoralcare listing.';

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
					PastoralCareSource::update($user);
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
				$this->dispatch("Pastoral Care");

		}

	}

?>