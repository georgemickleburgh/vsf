<?php

    /**
     * Class to easily manage sessions, rather than accessing the
     * $_SESSION superglobal directly.
     *
     * @package VSF
     * @version 0.0.1
     */

    namespace VSF;

    class Session 
    {
        
        /**
         * Sets the key to the value provided
         *
         * @param  string $key
         * @param  string $value
         */
        public static function set($key, $value)
        {
            $_SESSION[$key] = $value;
        }

        /**
         * Get a value by its key, returns false if the key is not set
         *
         * @param  string $key
         * @return string
         */
        public static function get($key)
        {
            if (!self::keyExists($key)) {
                return false;
            }

            return $_SESSION[$key];
        }

        /**
         * Check whether the key already has a value set
         *
         * @param  string $key
         * @return bool
         */
        public static function keyExists($key)
        {
            if (isset($_SESSION[$key])) {
                return true;
            }
            else {
                return false;
            }
        }

    }