<?php

	namespace VSF\Forms\Validators;

	class MinLength
	{

		public $errorMessage = 'The input has a minimum length';
		public $minimumLength = 0;

		function __construct($length)
		{
			$this->minimumLength = $length;
			$this->errorMessage = 'Your input needs to be at least ' . $length . ' characters long';
		}

		public function validate($value)
		{
			if(strlen($value) >= $this->minimumLength) {
				return true;
			}
			else {
				return false;
			}
		}

	}