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
         *
         * @deprecated
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
         * Generate a string with random characters, can
         * optionally use capital letters in the string
         *
         * @param  int
         * @param  bool
         * @return string
         */
        public static function generate($length = 8, $use_caps = false) 
        {
            // Check whether to use caps or not
            if (!$use_caps) {
                $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
            }
            else {
                $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            }
            
            // Start with an empty string
            $string = '';
            for($i = 0; $i < $length; $i++) {
                // Loop through the length of the string, adding a 
                // random character from the long string of characters
                $string .= $characters[rand(0, strlen($characters) - 1)];
            }
            
            return $string;
        }
    }