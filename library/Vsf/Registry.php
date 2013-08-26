<?php
	
	/**
	 * Class to handle the global variable registry. This is a
	 * singleton class, so only one may be instanciated
	 *
	 * @package VSF
	 * @version 0.0.1
	 */

	namespace VSF;

	class Registry extends Singleton 
	{

		private static $storage;

		/**
		 * Add a value to the registry, or change an existing value
		 * by specifying an existing key
		 * 
		 * @param string
		 * @param mixed
		 */
		public static function set($key, $value) 
		{
			self::$storage[$key] = $value;
		}

		/**
		 * Get a variable from the registry by the key
		 * 
		 * @param  string
		 * @return mixed
		 */
		public static function get($key) 
		{
			if(array_key_exists($key, self::$storage)) {
				return self::$storage[$key];
			}
			return false;
		}

	}