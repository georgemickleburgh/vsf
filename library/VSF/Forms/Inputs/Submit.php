<?php

	namespace VSF\Forms\Inputs;

	class Submit extends Input
	{

		public $template = 'SubmitInput.html';

		/**
		 * Override the constructor to avoid using a different name
		 * than submit
		 */
		public function __construct($name = 'submit')
		{
			parent::__construct('submit');
			$this->setValue($name);
		}

		// Override some functions which will not be needed for 
		// a submit element
		public function addValidator($validator) { return $this; }
		public function setLabel($label) { return $this; }
		public function setName($name) { return $this; }

	}