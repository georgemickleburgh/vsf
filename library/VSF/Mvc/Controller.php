<?php

	namespace VSF\Mvc;

	class Controller
	{

		public $db;
		public $settings;
		public $view;
		public $model;

		public function __construct($model = null)
		{
			$this->settings = \VSF\Registry::get('settings');
			$this->db = \VSF\Registry::get('db');

			if($model != null) {
				$this->model = $model;
			}
			
			$this->view = new View();

			$this->init();
		}

		public function init()
		{
			// Run any global things here
		}

	}