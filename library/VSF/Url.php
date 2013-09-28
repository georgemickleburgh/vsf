<?php

    namespace VSF;

    class Url 
    {

        /**
         * Get the URI, which also allows a custom header of 
         * HTTP_REQUEST_URI to be set, if the server does not naturally
         * support passing the URI with rewrites
         *
         * @static
         * @param  string
         * @return string
         */
        public static function getUri($header = 'HTTP_REQUEST_URI')
        {
            if (isset($_SERVER[$header])) {
                return $_SERVER[$header];
            }
            else {
                return $_SERVER['REQUEST_URI'];
            }
        }

        /**
         * Get the all of the segments of the URL and return them as
         * an array, splitting by / by default
         *
         * @static
         * @param string
         * @return array
         */
        public static function getSegments($split = '/') 
        {
            // Get the request URI and remove the first slash
            $url = ltrim(self::getUri(), '/');

            // Return false if URI is empty
            if (empty($url)) {
                return false;
            }

            // Split the URI by the split string
            $segments = explode($split, $url);

            return $segments;
        }

        /**
         * Get a specific segment by the index, without having to worry
         * about checking whether the index is set first. Returns false
         * when there is no segment set
         *
         * @static
         * @param  int
         * @return string
         */
        public static function getSegment($index)
        {
            // Increment the index so that 1 will be the first index
            $index--;

            $segments = self::getSegments();
        
            // Check whether the segment exists 
            if ($segments === false || !isset($segments[$index])) {
                return false;
            }
            else {
                // Return the session key value
                return $segments[$index];
            }
        }

        /**
         * Get the parameters that follow the ? in the URL
         * 
         * @return array
         */
        public static function getParams()
        {
            $uri = explode('?', $_SERVER['REQUEST_URI']);

            if(!isset($uri[1])) {
                return false;
            }

            $segments = explode('&', $uri[1]);

            $segmentArray = array();
            foreach($segments as $segment) {
                $e = explode('=', $segment);
                if(!empty($e[0]) && !empty($e[1])) {
                    $segmentArray[$e[0]] = $e[1];
                }
            }

            return $segmentArray;
        }

        /**
         * Get a specific parameter without having to worry about any undefined 
         * array index warnings, as it returns nothing if it does not exist
         *
         * @param string
         * @return string
         */
        public static function getParam($param)
        {
            $params = self::getParams();

            if(is_array($params)) {
                if(in_array($param, array_keys($params))) {
                    return $params[$param];
                }
                else {
                    return '';
                }
            }
        }

        /**
         * Get the last segment of the URL and return it as
         * a string
         *
         * @static
         * @return string
         */
        public static function getLastSegment() 
        {
            $url = $_SERVER['REQUEST_URI'];
            $segments = explode('/', $url);

            return end($segments);
        }

        /**
         * Easy redirects, including exiting the script execution
         *
         * @static
         * @param  string $path
         */
        public static function redirect($path = '/') 
        {
            header('location: '.$path);
            exit;
        }

        /**
         * Get the current protocol of the server, will return
         * either https:// or http://
         *
         * @static
         * @return string
         */
        public static function getProtocol() 
        {
            return ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        }

    }