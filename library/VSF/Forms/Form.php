<?php

    /**
     * Main VSF class to control the object oriented Forms.
     *
     * @package VSF/Forms
     * @version 0.0.1
     */
    
    namespace VSF\Forms;
    use VSF\Forms\Inputs;
    use VSF\Patterns\Registry;
    use VSF\String;
    use VSF\Url;

    class Form 
    {

        //Basic form variables
        public $elements;
        public $name;
        public $title;
        public $method = 'post';
        public $action = '';
        public $displayMode = 'list';
        public $errors = array();
        public $classes = array();

        //Application settings
        public $viewPath = '';
        public $template = 'Default.html';
        public $templatePath;

        /**
         * Constructor
         * 
         * @param string
         */
        public function __construct($name) 
        {
            $this->name = $name;
            $this->templatePath = dirname(__DIR__) . '/Views';
            $this->viewPath = $this->templatePath . '/Forms/';

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
                foreach($_POST as $k=>$v){
                    if($ele->getName() == $k)  {
                        $ele->setValue($v);
                    }
                }
            }
            $this->elements[$ele->getName()] = $ele;
            
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
         * Add a string to the array containing the classes for the form
         *
         * @param  string
         */
        public function addClass($class)
        {
            $this->classes[] = $class;
            return $this;
        }

        /**
         * Prepares the classes for display in the form
         *
         * @return  string
         */
        public function getClasses()
        {
            $string = 'class="';
            $counter = 0;
            foreach($this->classes as $class) {
                if($counter > 0) {
                    $string .= ' ';
                }
                $string .= $class;
                $counter++;
            }
            $string .= '"';

            if ($string != 'class=""') {
                return $string;
            }
            else {
                return '';
            }            
        }

        /**
         * Get Action
         */
        public function getAction()
        {
            if (empty($this->action)) {
                return Url::getUri();
            }
            else {
                return $this->action;
            }
        }

        /**
         * Echos the output from getContent
         *
         * @access public
         */
        public function display() 
        {
            $content = $this->getContent();
            echo $content;
        }

        /**
         * Gets the form's content and prepares it for displaying
         */
        public function getContent()
        {
            $rawContent = file_get_contents($this->viewPath . $this->template);

            $tags = array(
                '{method}' => $this->method,
                '{action}' => $this->getAction(),
                '{name}' => $this->name,
                '{title}' => $this->title,
                '{elements}' => $this->displayElements(),
                '{class}' => $this->getClasses(),
            );

            $content = str_replace(array_keys($tags), array_values($tags), $rawContent);
            return $content;
        }

        /**
         * Check for any errors that are thrown by the Form elements using the Input Validators
         *
         * @access public
         * @return boolean
         */
        public function isValid() 
        {
            if(!empty($_POST)) {
                $status = true;
                foreach($this->getElements() as $ele) {
                    $validate = $ele->isValid();
                    if($validate != false) {
                        if(is_array($status)) {
                            $status[$ele->name] = $validate;
                        }
                        else {
                            $status = array(
                                $ele->getName() => $validate
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
         * @deprecated Old MySQL functions
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

        /**
         * Move the submit button to the end of the elements array
         *
         * @return  void
         */
        public function reorderSubmit()
        {
            // Loop through all elements
            foreach ($this->elements as $key => $element) {
                // Check whether the input is the submit element
                if ($element instanceof Inputs\Submit) {
                    // Unset it from the array and re-add it to the end
                    $submit = $this->elements[$key];
                    unset($this->elements[$key]);
                    $this->elements[$key] = $submit;
                }
            }
        }

        /**
         * Title setter
         *
         * @param  string $title
         */
        public function setTitle($title)
        {
            $this->title = $title;
        }

        /**
         * Title getter
         *
         * @return string
         */
        public function getTitle()
        {
            return $this->title;
        }

    }