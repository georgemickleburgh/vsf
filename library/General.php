<?php

	/**
	 * General methods for the VSF
	 *
	 * @package VSF
	 * @version 0.0.1
	 */

	namespace VSF;

	class General 
	{
		
		/**
		 * Easy redirects
		 *
		 * @static
		 * @access public
		 * @param  string
		 */
		public static function redirect($path = '/') 
		{
			header('location: '.$path);
			exit;
		}

		/**
		 * Log messages to a file
		 * 
		 * @param  string
		 * @param  string
		 */
		public function log($content, $file='main') 
		{ 
			$file = 'logs/'.$file.'.log';
			$text = '['.date('d/m/Y H:i').'] ' . $content . PHP_EOL;

			echo $text;

			//Append text to log, or create new file if it doesn't exist
		}

		/**
		 * Get the URI segments, used for routing
		 * 
		 * @return array
		 */
		public static function getUriSegments() 
		{
			$array = array();

			if(!empty($_GET['a'])) {
				$uri = $_GET['a'];
				$explode = explode('/', $uri);

				foreach($explode as $v) {
					if(strlen($v)>0) {
						$array[] = $v;
					}
				}
			}
			
			return $array;
		}

		/**
		 * Send an email to an address
		 * 
		 * @param  string
		 * @param  string
		 * @param  string
		 * @return boolean
		 */
		public function email($to, $subject, $message) 
		{
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'To: '.$to. "\r\n";
			$headers .= 'From: Domain <noreply@domain.com>' . "\r\n";

			if(mail($to, $subject, $message, $headers)) {
				return true;
			}
			else {
				return false;
			}
		}

		/**
		 * Get the current protocol of the server
		 *
		 * @return string
		 */
		public static function getProtocol() 
		{
			return ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		}

	}