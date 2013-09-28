<?php

    namespace VSF\Forms\Inputs;

    class Email extends Input 
    {

        public $template = 'EmailInput.html';

        public function __construct($name) 
        {
            parent::__construct($name);
            $this->addValidator(new \VSF\Forms\Validators\Email());
        }

    }