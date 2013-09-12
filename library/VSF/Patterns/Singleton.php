<?php

	/**
	 * Base class to make future Singleton classes easier to write
	 *
	 * @package VSF
	 * @version 0.0.1
	 */
	
	namespace VSF\Patterns;

	abstract class Singleton 
	{

		protected static $instance;

		private function __construct() { }
		private function __clone() { }

		/**
		 * Get the currently active instance of this class
		 * or create a new one if one does not exist
		 * 
		 * @return Object
		 */
		final public static function getInstance() 
		{
	        if(!isset(static::$instance)) {
	            static::$instance = new static();
	        }
	 
	        return static::$instance;
	    }

	}