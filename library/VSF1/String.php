<?php

	/**
	 * String functions as part of VSF
	 *
	 * @package VSF
	 * @version 0.0.1
	 */

	namespace VSF;

	class String 
	{

		/**
		 * Escape string for old MySQL functions
		 * @param  string
		 * @return string
		 */
		public static function escape($string) 
		{
			return mysql_real_escape_string($string);
		}

		/**
		 * Clean string for displaying on the front end
		 * 
		 * @param  string
		 * @return string
		 */
		public static function clean($string) 
		{
			return htmlspecialchars($string);
		}

		/**
		 * Generate a string with random characters
		 *
		 * @param  int
		 * @return string
		 */
		public static function generate($length=8, $use_caps=false) 
		{
			if(!$use_caps) {
				$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
			}
			else {
				$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			}
			
			$string = '';
			for($i = 0; $i < $length; $i++) {
			    $string .= $characters[rand(0, strlen($characters) - 1)];
			}
			return $string;
		}
	}