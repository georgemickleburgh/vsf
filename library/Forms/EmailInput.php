<?php

	namespace VSF\Forms;

	class EmailInput extends Input 
	{

		public $template = 'EmailInput.html';

		public function __construct($name) 
		{
			parent::__construct($name);
			$this->addValidator(new \VSF\Forms\Validators\Email());
		}

	}