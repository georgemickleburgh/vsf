<?php

	namespace VSF\Forms\Validators;

	class Numeric extends Validator
	{

		public $errorMessage = 'Input must be an integer';

		public function isValid($value)
		{
			if(is_numeric($value)) {
				return true;
			}
			else {
				return false;
			}
		}

	}