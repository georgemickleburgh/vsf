<?php

	namespace VSF\Mvc;

	class View
	{
		
		public $pageTitle;
		public $settings;

		public $file;
		public $path;
		public $layout = 'layouts/layout.php';

		public function __construct()
		{
			$this->settings = \VSF\Registry::get('settings');
		}

		//Load view and extract data to be used by it
		public function load($file)
		{
			$this->file = $file;
			require_once($this->path . $this->layout);
		}

		public function getPageTitle()
		{
			if(!empty($this->pageTitle)) {
				return \VSF\String::clean($this->pageTitle) . '|' . $this->settings->siteTitle;
			}
			else {
				return $this->settings->siteTitle;
			}
		}

		public function getContent()
		{
			require_once($this->path . $this->file . '.php');
		}

		public function setLayout($string)
		{
			$this->layout = $string;
		}

		public function getLayout()
		{
			return $this->layout;
		}
		
	}