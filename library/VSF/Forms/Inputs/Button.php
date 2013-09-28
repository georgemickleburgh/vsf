<?php

    namespace VSF\Forms\Inputs;

    class Button extends Input
    {

        public $template = 'ButtonInput.html';

        // Override some functions which will not be needed
        public function setLabel($label) { return $this; }

    }