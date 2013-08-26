<?php

	namespace VSF\Forms\Validators;

	class NotEmpty extends Validator
	{

		public $errorMessage = 'Input must not be empty';

		public function validate($value)
		{
			if(!empty($value)) {
				return true;
			}
			else {
				return false;
			}
		}

	}