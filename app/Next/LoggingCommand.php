<?php 

	namespace App\Next;

	use Illuminate\Console\Command;

	class LoggingCommand extends Command {
		
		protected $log_buffer = '';
		protected $log_indent = '';
		protected $start = 0;

		public function log($message, $type) {
			$time = round(microtime(true) - $this->start, 2);
			$colours = array(
				'Info' => 'green',
				'Comment' => 'grey',
				'Error' => 'red',
			);
			$str = $this->log_indent."[$time"."s]: $message";
			$colour = $colours[$type];
			$this->log_buffer .= "<span style=\"color:$colour;\">$str</span>\n";
			return $str;
		}

		public function info($message) {
			parent::info($this->log($message, 'Info'));
		}

		public function error($message) {
			parent::error($this->log($message, 'Error'));
		}

		public function comment($message) {
			parent::comment($this->log($message, 'Comment'));
		}

		public function log_section($name) {
			$this->log_indent .= '    ';
			if ($name)
				$this->comment("Starting section: $name");
		}

		public function log_section_end($name, $error = false) {
			if ($name && $error)
				$this->error("Ending section: $name");
			else if ($name)
				$this->info("Ending section: $name");
			$this->log_indent = substr($this->log_indent, 0, -4);
		}

		public function dispatch($type) {
			curl_setopt_array(
			$chpush = curl_init(),
			array(
				CURLOPT_URL => "https://new.boxcar.io/api/notifications",
				CURLOPT_POSTFIELDS => array(
					"user_credentials" => '',
					"notification[title]" => "Keystone Next $type Error",
					"notification[long_message]" => nl2br($this->log_buffer),
					"notification[sound]" => "light",
				)));
			$ret = curl_exec($chpush);
			curl_close($chpush);
		}

		public function log_wake(){
			$this->log_buffer = date('D, d M Y H:i:s', time()) . "\n";
			$this->start = microtime(true);
		}
		
	}