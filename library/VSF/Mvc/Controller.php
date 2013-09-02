<?php

	namespace VSF\Mvc;

	class Controller
	{

		public $settings;
		public $view;

		public function __construct()
		{
			$this->settings = \VSF\Registry::get('settings');
			
			$this->view = new View();

			$this->init();
		}

		public function init()
		{
			// Run any global things here
			// Should be overriden in a Base Controller
		}

	}