<?php

namespace VSF\Mvc;
use VSF\Exception\BasicException;

class Router
{

    protected $config = null;
    protected $controller = 'Index';
    protected $method = 'index';
    protected $methodSuffix = 'Action';
    protected $module = null;
    protected $settings = null;

    /**
     * Constructor, takes the router config file to setup
     *
     * @param string $config
     */
    public function __construct($config = 'app/config/routing.php', $settings)
    {
        // Check whether the config file exists
        if (!file_exists($config)) {
            throw new BasicException('Could not open router config file');
        }

        $this->settings = $settings;

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
        $class = new $route($this->settings);

        $method = $this->method;

        // If the default module is being used methods will be in
        // URI index [1]
        if ($this->module['name'] == '_default' || (!empty($this->module['uses_segment_2']) && $this->module['uses_segment_2'])) {
            if ($this->module['methods'] != 'none') {
                if (isset($uri[1])) {
                    $method = $this->uriToMethod($uri[1]);
                }
            }
        }
        // Any other module will look for a method that is in
        // URI index [2]
        else {
            if ($this->module['methods'] != 'none') {
                if(isset($uri[2])) {
                    $method = $this->uriToMethod($uri[2]);
                }
            }
        }

        // Store variables
        $this->method = $method;

        // Construct method string
        $executeMethod = $method . $this->methodSuffix;
        return $class->$executeMethod();
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
            $this->module = $appRoute['routing']['_default'];
            $this->module['name'] = '_default';

            $paramArray = array(
                $appRoute['namespace'],
                $this->module['namespace'],
                'Controllers'
            );

            // If routing is for a single controller
            if ($this->module['type'] == 'single') {
                $paramArray[] = $this->module['controller'];
            }
            else {
                $paramArray[] = $this->controller;
            }

            return $this->createFQCN($paramArray);
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

                // Setup the module var
                $this->module = $appRoute['routing'][$module];
                $this->module['name'] = $module;
            }
        }

        // If no module found, revert to _default route
        if (!$moduleFound) {
            $class .= '\\' . $appRoute['routing']['_default']['namespace'];

            // Setup module var
            $this->module = $appRoute['routing']['_default'];
            $this->module['name'] = '_default';
        }

        $paramArray = array(
            $appRoute['namespace'],
            $this->module['namespace'],
            'Controllers',
        );

        // If routing is for a single controller
        if ($this->module['type'] == 'single') {
            $paramArray[] = $this->module['controller'];
        }
        else {
            if ($this->module['name'] != '_default' && !empty($uri[1])) {
                $this->controller = $this->uriToClass($uri[1]);
            }

            if($this->module['name'] == '_default' && !empty($uri[0])) {
                $this->controller = $this->uriToClass($uri[0]);
            }

            $paramArray[] = $this->controller;
        }

        return $this->createFQCN($paramArray);
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

    /**
     * Converts a string from a URI to be a usable method
     * name, replacing dashes and underscores with camelcase
     *
     * @param  string $string
     * @return string
     */
    private function uriToMethod($string)
    {
        $string = explode('?', $string);
        $string = $string[0];
        return lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $string))));
    }

    /**
     * Converts a string from a URI to be a usable class
     * name, replacing dashes with underscores and camelcase
     *
     * @param  string $string
     * @return string
     */
    private function uriToClass($string)
    {
        $string = explode('?', $string);
        $string = $string[0];
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Get method suffix
     *
     * @return string $methodSuffix
     */
    public function getMethodSuffix()
    {
        return $this->methodSuffix;
    }

    /**
     * Set method suffix
     *
     * @param string $methodSuffix
     */
    public function setMethodSuffix($methodSuffix)
    {
        $this->methodSuffix = $methodSuffix;
    }

}