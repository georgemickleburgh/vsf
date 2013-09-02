<?php

	namespace VSF\Mvc;
	use VSF\Exception\BasicException;

	class Router 
	{

		protected $config = null;
		protected $controller = 'Index';
		protected $method = 'index';
		protected $methodSuffix = 'Action';

		/**
		 * Constructor, takes the router config file to setup
		 * 
		 * @param string $config
		 */
		public function __construct($config = 'app/config/routing.php') 
		{
			// Check whether the config file exists
			if (!file_exists($config)) {
				throw new BasicException('Could not open router config file');
			}

			// Add the routing settings to the config var
			$this->config = require_once($config);

			// Check whether the routing config is well formed
			if (!isset($this->config['application'])) {
				throw new BasicException('Malformed routing config. Routing must include an "application" key');
			}
		}

		/**
		 * Gets the route from the getRoute method, constructs the
		 * FQCN and then executes the correct method on the class
		 *
		 * @param  array $uri
		 * @return void
		 */
		public function execute($uri)
		{
			$route = $this->getRoute($uri);

			$method = $this->method;

			// Check if method needed
			// Check method type
			// Construct method string
			// Execute
		}

		/**
		 * Gets the route from the config from an array of the
		 * split URI, for example, from VSF\Url::getSegments()
		 *
		 * @param array $uri
		 */
		public function getRoute($uri)
		{
			$appRoute = $this->config['application'];

			// Firstly, check if the routing is for the homepage, i.e no segments
			if ($uri === false) {
				return $this->createFQCN(array(
					$appRoute['namespace'],
					$appRoute['routing']['_default']['namespace'],
					$this->controller
				));
			}

			// Check whether the parameter is an array
			if (!is_array($uri)) {
				throw new BasicException('Routing requires an array of URI segments');
			}

			$class = $appRoute['namespace'];
			$modules = array_keys($appRoute['routing']);

			// Check for module
			$moduleFound = false;
			foreach($modules as $module) {
				if ($uri[0] == $module) {
					$moduleFound = true;
					$class .= '\\' . $appRoute['routing'][$module]['namespace'];
				}
			}

			// If no module found, revert to _default route
			if (!$moduleFound) {
				$class .= '\\' . $appRoute['routing']['_default']['namespace'];
			}



			var_dump($class);
		}

		/**
		 * Constructs the FQCN using an array of namespaces
		 * and the class name
		 *
		 * @param  array $namespaces
		 * @return string
		 */
		public function createFQCN($namespaces)
		{
			if (!is_array($namespaces)) {
				return false;
			}

			$className = '';
			foreach($namespaces as $ns) {
				$className .= '\\' . $ns;
			}

			return $className;
		}

	}