<?php

	namespace VSF\Forms\Validators;

	class Alphanumeric extends Validator
	{

		public $errorMessage = 'Input must be alphanumeric';

		public function validate($value) {
			if(preg_match('/^([a-zA-Z0-9])+$/', $value)) {
				return true;
			}
			else {
				return false;
			}
		}

	}