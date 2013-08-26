<?php

	namespace VSF\Forms\Validators;

	abstract class Validator
	{

		public $errorMessage = 'Invalid Validator';

		public function validate($value) {
			return false;
		}

	}