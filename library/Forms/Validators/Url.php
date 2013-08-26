<?php

	namespace VSF\Forms\Validators;

	class Url extends Validator
	{

		public $errorMessage = 'Input must be a URL';

		public function validate($value)
		{
			// Add the http:// protocol to the string if it has none
			if(substr($value, 0, 4) != 'http') {
				$value = 'http://' . $value;
			}

			if(filter_var($value, FILTER_VALIDATE_URL)) { 
				return true;
			}
			else {
				return false;
			}
		}

	}