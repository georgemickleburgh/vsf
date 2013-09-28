<?php

    namespace VSF;
    use VSF\Patterns\Registry;

    class Application 
    {

        /**
         * Setup the base application, including starting sessions,
         * settings timezone etc
         * 
         * @return void
         */
        public static function setup($settingsFile)
        {
            // Deal with global settings first
            session_start();

            // Split the other sections into seperate functions so 
            // they can be run individually if needed
            self::setupSettings($settingsFile);
            self::setupDoctrine();
        }

        /**
         * Setup the settings file
         *
         * @return void
         */
        public static function setupSettings($settingsFile)
        {
            // Now get the settings file and parse it
            $configArray = File::parseIni($settingsFile);

            // Check to see if there is a valid config which matches
            // the SERVER_NAME and check that its not CLI mode
            if (php_sapi_name() != 'cli') {
                if (!isset($configArray[$_SERVER['SERVER_NAME']])) {
                    throw new Exception\BasicException('There is no configuration for this server name');
                }
                else {
                    $configArray = $configArray[$_SERVER['SERVER_NAME']];
                }
            }
            else {
                if (isset($configArray['console'])) {
                    $configArray = $configArray['console'];
                }
                else {
                    throw new Exception\BasicException('There is no configuration for console applications');
                }
            }

            // Create an object full of the settings variables
            // Supports up to 2 levels of recursion for creating
            // objects, after that the values will be stored in 
            // arrays. E.g:
            // $settings->val->something['and']['another']
            $settings = new \StdClass();
            // Depth: 1
            foreach($configArray as $setting => $value) {
                if(is_array($value)) {
                    $valClass = new \StdClass();
                    // Depth: 2
                    foreach($value as $key=>$val) {
                        $valClass->$key = $val;
                    }
                    $value = $valClass;
                }
                // Finally assign all settings
                $settings->$setting = $value;
            }

            // Settings specific configuration options
            if(isset($settings->timezone)) {
                date_default_timezone_set($settings->timezone);
            }
            else {
                date_default_timezone_set('Europe/London');
            }

            // Add all neccessary variables to the Registry for later use
            Registry::set('settings', $settings);
        }

        /**
         * Setup the application to work with doctrine
         * 
         * @return void
         */
        public static function setupDoctrine()
        {
            $settings = Registry::get('settings');
            
            // Doctrine specific settings
            if(isset($settings->doctrine) && $settings->doctrine->enabled == 1) {
                // Setup all variables here, with checks to see whether there
                // are overrides set in the settings file
                $devMode = ($settings->environment == "development" || $settings->environment == "dev") ? true : false;
                $paths = $settings->doctrine->entityPaths;
                $proxyDir = (isset($settings->doctrine->proxyDir)) ? $settings->doctrine->proxyDir : 'data';
                // Now to the actual DB parameters for the connection
                $dbParams = array(
                    'driver'   => $settings->doctrine->driver,
                    'user'     => $settings->doctrine->user,
                    'password' => $settings->doctrine->password,
                    'dbname'   => $settings->doctrine->dbname,
                );

                // Now setup Doctrine
                $doctrine = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($paths, $devMode);
                $doctrine->setProxyDir($proxyDir);

                if($devMode) {
                    // Development should use a simple Array cache
                    $doctrine->setQueryCacheImpl(new \Doctrine\Common\Cache\ArrayCache());
                }
                else {
                    // Production should use APC Cache
                    $doctrine->setQueryCacheImpl(new \Doctrine\Common\Cache\ApcCache());
                }

                $entityManager = \Doctrine\ORM\EntityManager::create($dbParams, $doctrine);
                Registry::set('em', $entityManager);
            }
        }

    }