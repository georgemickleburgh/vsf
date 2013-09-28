<?php

    namespace VSF\Forms\Inputs;
    use VSF\String;

    abstract class Input
    {

        public $name;
        protected $properties;
        protected $validators;
        protected $value;
        protected $label;
        protected $required;
        public $errors;

        //Application setup values
        public $viewPath;
        public $settings;
        public $template = '';
        public $templatePath;

        public function __construct($name)
        {
            $this->name = $name;
            $this->label = ucfirst($name);
            $this->viewPath = dirname(__DIR__) . '/../Views' . $this->templatePath . '/Inputs/';
        }

        /**
         * __toString override to allow displaying of the form without
         * having to directly call the display function
         */
        public function __toString() {
            $this->display();
            return '';
        }

        public function setName($name)
        {
            $this->name = $name;
            return $this;
        }

        public function getName()
        {
            return $this->name;
        }

        public function getRequired()
        {
            return $this->required;
        }
        public function setRequired($required)
        {
            $this->required = $required;
            return $this;
        }

        public function getProperties()
        {
            return $this->properties;
        }

        public function addProperty($type, $value = null)
        {
            if (!empty($value)) {
                $this->properties[$type][] = $value;
            }
            else {
                $this->proprties[$type] = true;
            }
            return $this;
        }

        /**
         * Get the string of properties to be added to the element
         * @return [type] [description]
         */
        protected function displayProperties()
        {
            // Check whether there are any properties
            if (!empty($this->properties)) {
                $string = '';

                // Loop through all keys, which are the property type
                foreach($this->properties as $type => $value) {
                    // Check whether there are any already in the string to add spaces
                    if (!empty($string)) {
                        $string .= ' ';
                    }

                    // If the value is true it is a standalone property i.e autofocus
                    if ($value !== true) {
                        // Add the property has a value it needs to have a = symbol
                        $string .= $type . '="';
                        $c = 0;
                        // Loop through each value and add 
                        foreach($value as $v) {
                            // Add a space if there are other elements
                            if ($c > 0) {
                                $string .= ' ';
                            }
                            $string .= $v;
                            $c++;
                        }
                        // Finish the string and add the last quotation
                        $string .= '"';
                    }
                    else {
                        // Simply add the "type" to the string if the value is true
                        $string .= $type;
                    }
                }

                // Return the constructed string
                return $string;
            }
            else {
                return '';
            }
        }

        public function getValidators()
        {
            return $this->validators;
        }

        public function addValidator($validator)
        {
            $this->validators[] = $validator;
            return $this;
        }

        public function setValue($value)
        {
            $this->value = $value;
            return $this;
        }

        public function getValue()
        {
            return $this->value;
        }

        public function setLabel($label)
        {
            $this->label = $label;
            return $this;
        }

        public function getLabel()
        {
            return $this->label;
        }

        public function getOptions()
        {
            return true;
        }

        /**
         * Return any errors in string format
         *
         * @return string
         */
        public function getErrors()
        {
            if(!empty($this->errors)) {
                $string = '';
                foreach($this->errors as $error) {
                    $string .= '<p class="text-error">' . $error . '</p>';
                }

                return $string;
            }
            return '';
        }

        /**
         * Get the path to the current template
         *
         * @return string
         */
        public function getViewPath()
        {
            return $this->viewPath;
        }

        /**
         * Set the view path
         *
         * @param string $path
         */
        public function setViewPath($path)
        {
            $this->viewPath = $path;
            return $this;
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
         * Get the content, from the template, replacing any tags with function calls
         * 
         * @return string
         */
        public function getContent()
        {
            $rawContent = file_get_contents($this->viewPath . $this->template);

            $tags = array(
                '{name}' => String::clean($this->name),
                '{label}' => String::clean($this->label),
                '{value}' => String::clean($this->value),
                '{properties}' => $this->displayProperties(),
                '{errors}' => $this->getErrors(),
                '{options}' => $this->getOptions(),
            );

            $content = str_replace(array_keys($tags), array_values($tags), $rawContent);
            return $content;
        }

        /**
         * Check the input for any errors via all validators
         * 
         * @return boolean
         */
        public function isValid()
        {
            $errors = array();

            // Check if the field is required and not empty
            if ($this->required == true && empty($this->value)) {
                $this->errors[] = 'This field is required';
                return $this->errors;
            }

            if (!empty($this->validators)) {
                foreach($this->validators as $validator) {
                    if(method_exists($validator, 'isValid')) {
                        $check = $validator->isValid($this->value);
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

    }