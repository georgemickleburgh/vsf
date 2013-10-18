<?php

    namespace VSF\Mvc;
    use VSF\Patterns\Registry;
    use VSF\String;

    class View
    {
        
        public $pageTitle;
        public $settings;

        public $file;
        public $path;
        public $layout = '/layouts/layout.php';
        public $templateDir = 'templates';
        public $helper;

        protected $variables = array();

        public function __construct()
        {
            $this->settings = Registry::get('settings');
            $this->siteTitle = $this->settings->site->title;
        }

        //Load view and extract data to be used by it
        public function load($file)
        {
            $this->file = $file;
            require_once($this->path . $this->layout);
        }

        // Set variables to be received later
        public function set($key, $value)
        {
            $this->variables[$key] = $value;
        }
        // Retrieve a variable by its key
        public function get($key)
        {
            if (isset($this->variables[$key])) {
                return $this->variables[$key];
            }
            else {
                return null;
            }
        }

        public function getPageTitle()
        {
            if (!empty($this->pageTitle)) {
                return String::clean($this->pageTitle) . ' | ' . $this->siteTitle;
            }
            else {
                return $this->settings->siteTitle;
            }
        }

        public function setPageTitle($pageTitle)
        {
            $this->pageTitle = $pageTitle;
        }

        public function getSiteTitle()
        {
            return $this->siteTitle;
        }

        public function setSiteTitle($siteTitle)
        {
            $this->siteTitle = $siteTitle;
        }

        public function getContent()
        {
            require_once($this->path . '/' . $this->templateDir . '/' . $this->file . '.php');
        }

        public function setLayout($string)
        {
            $this->layout = $string;
        }

        public function getLayout()
        {
            return $this->layout;
        }

        public function setPath($path)
        {
            $this->path = $path;
        }

        public function getPath()
        {
            return $this->path;
        }

        public function setHelperClass($class)
        {
            $this->helper = $class;
        }
        
    }