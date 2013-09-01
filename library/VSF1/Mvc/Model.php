<?php

	namespace VSF\Mvc;

	class Model
	{

		public $db;

		public $_table = "";

		public function __construct() {
			$this->db = \VSF\Registry::get('db');
		}

	}