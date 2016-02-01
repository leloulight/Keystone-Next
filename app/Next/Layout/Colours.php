<?php

	namespace App\Next\Layout;

	class Colours {

		const HUE_MIN = 0;
		const HUE_MAX = 359;

		const SUBJECT_SATURATION = 80;
		const SUBJECT_LIGHTNESS = 45;

		const DUE_SATURATION = 70;
		const DUE_LIGHTNESS = 50;
		const DUE_MIN = 190;
		const DUE_NOW = 20;
		const DUE_MAX = 0;

		public static $cached_subject_hsl = [];

		public static function subject_hue($subject) {
			$seed = 0;

			for ($i = 0; $i < strlen($subject); $i++) { 
				$seed += ord($subject[0]);
			}

			srand($seed);

			return rand(self::HUE_MIN, self::HUE_MAX);
		}

		public static function subject_hsl($subject) {
			if (!isset(static::$cached_subject_hsl[$subject]))
				static::$cached_subject_hsl[$subject] = static::hsl(static::subject_hue($subject), static::SUBJECT_SATURATION, static::SUBJECT_LIGHTNESS);
			return static::$cached_subject_hsl[$subject];
		}

		public static function due_hue($delta) {
			if ($delta < 0)
				// The full due colour occurs 1 day after
				return static::DUE_MAX + max(1 + $delta / 60 / 60 / 24, 0) * (static::DUE_NOW - static::DUE_MAX);
			else
				// 0% due colours occurs 1 week before
				return static::DUE_NOW + min($delta / 60 / 60 / 24 / 7, 1) * (static::DUE_MIN - static::DUE_NOW);
		}

		public static function due_hue_inverse($delta) {
			return static::DUE_MIN - min($delta / 60 / 60 / 24 / 7, 1) * (static::DUE_MIN - static::DUE_MAX);
		}

		public static function due_hsl_inverse($delta) {
			return static::hsl(static::due_hue_inverse($delta), static::DUE_SATURATION, static::DUE_LIGHTNESS);
		}

		public static function due_hsl($delta) {
			return static::hsl(static::due_hue($delta), static::DUE_SATURATION, static::DUE_LIGHTNESS);
		}

		public static function hsl($hue, $saturation, $lightness) {
			return "hsl($hue, $saturation%, $lightness%);";
		}
	}

?>