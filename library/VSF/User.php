<?php

	/**
	 * User methods for login-based systems as part of VSF
	 *
	 * @package VSF
	 * @version 0.0.1
	 */

	namespace VSF;
	use VSF\Session;

	class User 
	{
		
		const SESSION_KEY 	 = 'VSFu_logged_in';
		const SESSION_PREFIX = 'VSFu_';

		/**
		 * Check the session variable to see if the user is logged in
		 * 
		 * @return boolean
		 */
		public static function isLoggedIn() 
		{
			// Check whether the session index with the SESSION_KEY
			// has been set
			if (Session::keyExists(self::SESSION_KEY)) {
				return true;	
			}
			else {
				return false;
			}
		}

		/**
		 * Get a user variable from the current session, which
		 * deals with whether the user is logged in, and returns the
		 * value that was set when logging in
		 *
		 * @param  string $key
		 * @return string
		 */
		public static function get($key)
		{
			// Check whether logged in
			if (!self::isLoggedIn()) {
				return false;
			}

			// Return the value of the Session get, which deals
			// with null values or unset keys by itself
			return Session::get(self::SESSION_PREFIX . $key);
		}

		/**
		 * Login with an associative array of parameters to 
		 * store to the session
		 *
		 * @param array
		 */
		public static function login($params)
		{
			// First, check whether the user is already logged in
			if (self::isLoggedIn()) {
				return false;
			}

			// If not logged in, set the SESSION_KEY to true
			Session::set(self::SESSION_KEY, true);

			// Loop through all of the parameters and add
			// them to the current session
			foreach($params as $key => $value) {
				Session::set(self::SESSION_PREFIX . $key, $value);
			}

			return true;
		}

		/**
		 * Logout, deleting any of the prefixed keys in the process.
		 * Returns false if the user is not logged in
		 *
		 * @return  bool
		 */
		public static function logout()
		{
			// Check if the user is logged in
			if (!self::isLoggedIn()) {
				return false;
			}

			// Loop through the session variables
			foreach($_SESSION as $key => $value) {
				// Check whether the key starts with the prefix and delete
				// it. This should force the logout
				if (strpos($key, self::SESSION_PREFIX) === 0) {
					unset($_SESSION[$key]);
				}
			}

			return true;
		}

	}