<?php

    /**
     * Main VSF class to control the object oriented Forms.
     *
     * @package VSF/Forms
     * @version 0.0.1
     */
    
    namespace VSF\Forms;

    class Form 
    {

        //Basic form variables
        public $elements;
        public $name;
        public $method = 'post';
        public $action = '';
        public $submitText = 'Submit';
        public $displayMode = 'list';
        public $errors = array();

        //Application settings
        public $settings;
        public $viewPath = '';
        public $template = 'Default.html';
        public $templatePath = FRAMEWORK_VIEWS_PATH;

        /**
         * Constructor
         * 
         * @param string
         */
        public function __construct($name) 
        {
            $this->settings = \VSF\Registry::get('settings');
            $this->name = $name;
            $this->action = '/' . \VSF\String::clean($_GET['a']);
            $this->viewPath = $this->templatePath . '/forms/';

            $this->init();
        }

        /**
         * __toString override to allow displaying of the form without
         * having to directly call the display function
         */
        public function __toString() {
            $this->display();
            return '';
        }

        /**
         * Standard init function which should be used when overriding
         * this class with a specific form
         */
        public function init() {}

        /**
         * Add an element to the form.
         *
         * Can be used as: $form->addElement(new \VSF\Forms\TextInput());
         *
         * @access public
         * @param Input
         * @return Form
         */
        public function addElement($ele) 
        {  
            if(!empty($_POST)) {
                foreach($_POST as $k=>$v) {
                    if($ele->name == $k) {
                        $ele->value = $v;
                    }
                }
            }
            $this->elements[$ele->name] = $ele;
            
            return $this;
        }

        /**
         * Removes an element from the elements array using the array key
         *
         * @access public
         * @param string
         */
        public function removeElement($key) 
        {
            if(!empty($this->elements[$key])) {
                array_pop($this->elements, $key);
            }
        }

        /**
         * Get all the elements from the form as an array
         *
         * @access public
         * @return array
         */
        public function getElements() 
        {
            $array = array();
            if(!empty($this->elements)) {
                foreach($this->elements as $element) {
                    $array[] = $element;
                }
            }
            return $array;
        }

        /**
         * Get an individual element by it's name
         *
         * @access public
         * @return Input
         */
        public function getElement($name)
        {
            if(!empty($this->elements)) {
                foreach($this->elements as $element) {
                    if($element->getName() == $name) {
                        return $element;
                    }
                }
            }
            // If it has got this far, it has found no match
            return false;
        }

        /**
         * Get all of the Form's Input-type object content's, concatenate them together and return as a string
         *
         * @access public
         * @return string
         */
        public function displayElements() 
        {
            $content = $this->getFormBefore();
            foreach($this->getElements() as $element) {
                $content .= $this->getElementBefore() . $element->getContent() . $this->getElementAfter();
            }
            $content .= $this->getFormAfter();
            return $content;
        }

        /**
         * Basic function to get the HTML for the submit button, with dynamic value
         *
         * @access public
         * @return string
         */
        public function getSubmitButton() 
        {
            return '<input type="submit" value="'.$this->submitText.'" id="submit" />';
        }

        /**
         * Creates the Form in HTML format from combining the elements and using the form template
         *
         * @access public
         * @return string
         */
        public function display() 
        {
            $rawContent = file_get_contents($this->viewPath . $this->template);

            $tags = array(
                '{method}' => $this->method,
                '{action}' => $this->action,
                '{name}' => $this->name,
                '{elements}' => $this->displayElements(),
                '{submit}' => $this->getSubmitButton(),
            );

            $content = str_replace(array_keys($tags), array_values($tags), $rawContent);
            echo $content;
        }

        /**
         * Check for any errors that are thrown by the Form elements using the Input Validators
         *
         * @access public
         * @return boolean
         */
        public function validate() 
        {
            if(!empty($_POST)) {
                $status = true;
                foreach($this->getElements() as $ele) {
                    $validate = $ele->validate();
                    if($validate != false) {
                        if(is_array($status)) {
                            $status[$ele->name] = $validate;
                        }
                        else {
                            $status = array(
                                $ele->name => $validate
                            );
                        }
                    }
                }
                if(is_array($status)) {
                    $this->errors = $status;
                    return false;
                }
                else {
                    return true;
                }
            }
            else {
                return false;
            }
        }

        /**
         * Get the HTML content that is before each element.
         *
         * The HTML will depend on what value is set in $this->displayMode 
         *
         * @access private
         * @return string
         */
        private function getElementBefore() 
        {
            switch($this->displayMode) {
                case 'list': return '<li>'; break;
                case 'linear': return ''; break;
                default: return '<li>';
            }
        }

        /**
         * Get the HTML content that is after each element.
         *
         * The HTML will depend on what value is set in $this->displayMode 
         *
         * @access private
         * @return string
         */
        private function getElementAfter()
        {
            switch($this->displayMode) {
                case 'list': return '</li>'; break;
                case 'linear': return ''; break;
                default: return '</li>';
            }
        }

        /**
         * Get the HTML content that is before the form.
         *
         * The HTML will depend on what value is set in $this->displayMode 
         *
         * @access private
         * @return string
         */
        private function getFormBefore()
        {
            switch($this->displayMode) {
                case 'list': return '<ul>'; break;
                case 'linear': return ''; break;
                default: return '<ul>';
            }
        }

        /**
         * Get the HTML content that is after the form.
         *
         * The HTML will depend on what value is set in $this->displayMode 
         * 
         * @access private
         * @return string
         */
        private function getFormAfter()
        {
            switch($this->displayMode) {
                case 'list': return '</ul>'; break;
                case 'linear': return ''; break;
                default: return '</ul>';
            }
        }

        /**
         * Static function to iterate through all _POST variables and clean them
         *
         * @access public
         * @static
         */
        public static function cleanPost()
        {
            if(!empty($_POST)) {
                foreach($_POST as $key=>$value) {
                    if(is_array($_POST[$key])) {
                        foreach($_POST[$key] as $k=>$v) {
                            $_POST[$key][$k] = \VSF\String::escape($v);
                        }
                    }
                    else {
                        $_POST[$key] = \VSF\String::esacpe($value);
                    }
                }
            }
        }

    }