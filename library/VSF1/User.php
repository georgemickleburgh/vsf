<?php

	/**
	 * User methods for login-based systems as part of VSF
	 *
	 * @package VSF
	 * @version 0.0.1
	 */

	namespace VSF;

	class User 
	{
		
		const SESSION_KEY = 'logged_in';

		/**
		 * Check the session variable to see if the user is logged in
		 * 
		 * @return boolean
		 */
		public static function isLoggedIn() 
		{
			if(!empty($_SESSION[self::SESSION_KEY])) {
				return true;
			}
			else {
				return false;
			}
		}

		/**
		 * Get the current user's ID
		 *
		 * @static
		 * @return  int
		 */
		public static function getUserId() 
		{
			if(self::isLoggedIn()) {
				return $_SESSION['user_id'];
			}
			else {
				return false;
			}
		}

	}