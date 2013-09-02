<?php

	namespace VSF;

	class Url 
	{

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
			$url = ltrim($_SERVER['REQUEST_URI'], '/');

			// Return false if URI is empty
			if (empty($url)) {
				return false;
			}

			// Split the URI by the split string
			$segments = explode($split, $url);

			return $segments;
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

	}