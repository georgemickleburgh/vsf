<?php

	namespace VSF\Forms\Validators;

	class MinLength
	{

		public $errorMessage = 'The input has a maximum length';
		public $maximumLength = 0;

		function __construct($length)
		{
			$this->maximumLength = $length;
			$this->errorMessage = 'Your input needs to be less than ' . $length . ' characters long';
		}

		public function isValid($value)
		{
			if(strlen($value) <= $this->minimumLength) {
				return true;
			}
			else {
				return false;
			}
		}

	}