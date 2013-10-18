<?php

    namespace VSF\Mvc;
    use VSF\Patterns\Registry;

    class Controller
    {

        public $view;

        /**
         * Constructor
         * 
         * @param object $settings
         */
        public function __construct($settings)
        {
            $this->view = new View($settings);
            $this->init();
        }

        public function init()
        {
            // Run any global things here
            // Should be overriden in a Base Controller
        }

    }