<?php

	/**
	 * Main VSF class to deal with Input classes
	 *
	 * @package VSF/Forms
	 * @version 0.0.1
	 */

	namespace VSF\Forms;

	abstract class Input 
	{

		//Form specific values
		public $name;
		public $label;
		public $value;
		public $errors;
		public $errorBorder = false;
		public $validators = array();

		//Application setup values
		public $viewPath;
		public $settings;
		public $template = '';
		public $templatePath = FRAMEWORK_VIEWS_PATH;

		/**
		 * Constructor
		 * 
		 * @param string
		 */
		function __construct($name) 
		{
			$this->settings = \VSF\Registry::get('settings');
			$this->name = $name;
			$this->label = ucfirst($name);
			$this->viewPath = $this->templatePath . '/inputs/';
		}

		/**
		 * Echo all of the markup from getContent
		 * 
		 * @return void
		 */
		public function display()
		{
			echo $this->getContent();
		}

		/**
		 * Get the clean value from the Input
		 * 
		 * @return string
		 */
		public function getValue()
		{
			return \VSF\String::clean($this->value);
		}

		/**
		 * Get the raw value from the Input
		 * 
		 * @return string
		 */
		public function getRawValue()
		{
			return $this->value;
		}

		/**
		 * Add a class to the field if it has an error
		 *
		 * @return string
		 */
		public function errorClass()
		{
			if(!empty($this->errors) || $this->errorBorder) {
				return 'class="error"';
			}
			return '';
		}

		/**
		 * Return any errors in string format
		 *
		 * @return string
		 */
		public function getErrors()
		{
			if(!empty($this->errors)) {
				$string = '<div class="error-display">';
				foreach($this->errors as $error) {
					$string .= '<p>' . $error . '</p>';
				}
				$string .= '</div>';

				return $string;
			}
			return '';
		}

		/**
		 * Get the content, from the template, replacing any tags with function calls
		 * 
		 * @return string
		 */
		public function getContent()
		{
			$rawContent = file_get_contents($this->viewPath . $this->template);

			$tags = array(
				'{name}' => \VSF\String::clean($this->name),
				'{label}' => \VSF\String::clean($this->label),
				'{value}' => \VSF\String::clean($this->value),
				'{error_class}' => $this->errorClass(),
				'{errors}' => $this->getErrors(),
				'{options}' => $this->getOptions(),
			);

			$content = str_replace(array_keys($tags), array_values($tags), $rawContent);
			return $content;
		}

		/**
		 * Add validot class to the input
		 * 
		 * @param Validator
		 */
		public function addValidator($validator)
		{
			$this->validators[] = $validator;
		}

		/**
		 * getOptions holder for dropdown inputs
		 * 
		 * @return string
		 */
		public function getOptions()
		{
			return false;
		}

		/**
		 * Check the input for any errors via all validators
		 * 
		 * @return boolean
		 */
		public function validate()
		{
			$errors = array();
			if(!empty($this->validators)) {
				foreach($this->validators as $validator) {
					if(method_exists($validator, 'validate')) {
						$check = $validator->validate($this->value);
						if($check === false) {
							$errors[] = $validator->errorMessage;
						}
					}
					else {
						throw new Exception('Invalid Validator: ' . $validator);
					}
				}
			}
			if(!empty($errors)) {
				$this->errors = $errors;
				return $errors;
			}
			else {
				return false;
			}
		}

		/**
		 * Getter for name
		 * 
		 * @return string
		 */
		public function getName()
		{
			return $this->name;
		}

		/**
		 * Setter for name
		 * 
		 * @param string
		 */
		public function setName($name)
		{
			$this->name = $name;
		}

		/**
		 * Getter for label
		 *
		 * @return string
		 */
		public function getLabel() {
			return $this->label;
		}

		/**
		 * Setter for label
		 *
		 * @param string
		 */
		public function setLabel($label) {
			$this->label = $label;
		}

	}