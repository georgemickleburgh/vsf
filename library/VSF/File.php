<?php
    
    /**
     *  Main class for file based functions for VSF
     *
     * @package VSF
     * @version 0.0.1
     */

    namespace VSF;

    class File 
    {
        
        /**
         * An extended version of parse_ini_file, which supports inherited
         * properties, using a colon (:) to denote the settings, e.g.
         * 
         * [ example.com : live]
         * array.val  = 1
         * array.val2 = 2
         * 
         * This will include of the properties from inside the "example.com"
         * block and all of the settings from the "live" block. The "." 
         * in the property will be split into an array of values
         * 
         * @param  string
         * @return array
         */
        public static function parseIni($filename, $seperator = ':')
        {
            // Get the ini file and create a new empty array
            $ini = parse_ini_file($filename, true);
            $config = array();

            // Loop through each section
            foreach($ini as $namespace => $properties) {
                if(count(explode($seperator, $namespace)) == 1) {
                    $name = $namespace;
                }
                else {
                    list($name, $extends) = explode($seperator, $namespace);
                }
                
                $name = trim($name);

                if(!empty($extends)) {
                    $extends = trim($extends);
                }

                // Create namespace if necessary
                if(!isset($config[$name])) $config[$name] = array();
                // Inherit base namespace
                if(!empty($extends) && isset($ini[$extends])) {
                    foreach($ini[$extends] as $prop => $val) {
                        $config[$name][$prop] = $val;
                    }
                }

                // Overwrite / Set current namespace values
                foreach($properties as $prop => $val) {
                    $config[$name][$prop] = $val;

                    // Split the properties, to check for . and array values
                    // $split = explode('.', $prop);
                    // if(count($split) == 2) {
                    //  $config[$name][$split[0]][$split[1]] = $val;
                    //  unset($config[$name][$prop]);
                    // }
                }
                
            }

            foreach($config as $name => $section) {
                foreach($section as $setting => $value) {
                    // Split the properties, to check for . and array values
                    $split = explode('.', $setting);
                    if(count($split) == 2) {
                        $config[$name][$split[0]][$split[1]] = $value;
                        unset($config[$name][$setting]);
                    }
                }
            }
                        
            return $config;
        }

    }