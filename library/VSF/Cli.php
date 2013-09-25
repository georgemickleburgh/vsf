<?php

	namespace VSF;

	class Cli
	{

		// Foreground Colors
		const FG_BLACK 	     = '0;30';
		const FG_DARK_GRAY   = '1;30';
		const FG_BLUE	     = '0;34';
		const FG_LIGHT_BLUE  = '1;34';
		const FG_GREEN		 = '0;32';
		const FG_LIGHT_GREEN = '1;32';
		const FG_CYAN		 = '0;36';
		const FG_LIGHT_CYAN  = '1;36';
		const FG_RED		 = '0;31';
		const FG_LIGHT_RED	 = '1;31';
		const FG_PURPLE		 = '0;35';
		const FG_LIGHT_PURPLE= '1;35';
		const FG_BROWN 		 = '0;33';
		const FG_YELLOW		 = '1;33';
		const FG_LIGHT_GRAY  = '0;37';
		const FG_WHITE		 = '1;37';

		// Background Colors
		const BG_BLACK		= '40';
		const BG_RED		= '41';
		const BG_GREEN		= '42';
		const BG_YELLOW		= '43';
		const BG_BLUE		= '44';
		const BG_MAGNETA	= '45';
		const BG_CYAN		= '46';
		const BG_LIGHT_GREY	= '47';

		/**
		 * CLI Initialisation
		 */
		public function __construct()
		{

		}

		/**
		 * Prints a string to the CLI followed by a new line. Also
		 * accepts a color for the text as the second and third parameter.
		 *
		 * @param string $text
		 * @param string $foregroundColor Optional
		 * @param string $backgroundColor Optional
		 */
		public function printLine($text, $foregroundColor = null, $backgroundColor = null)
		{
			$string = "";
 
			// Check if given foreground color found
			if (isset($foregroundColor)) {
				$string .= "\033[" . $foregroundColor . "m";
			}
			// Check if given background color found
			if (isset($backgroundColor)) {
				$string .= "\033[" . $backgroundColor . "m";
			}

			// Add string and end coloring
			$string .=  $text . "\033[0m";

			echo $string . PHP_EOL;
		}

	}