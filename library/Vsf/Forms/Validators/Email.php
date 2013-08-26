<?php

	namespace VSF\Forms\Validators;

	class Email extends Validator
	{

		public $errorMessage = 'Input must be an email';

		public function validate($value)
		{
			if(preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', $value)) { 
				return true;
			}
			else {
				return false;
			}
		}

	}