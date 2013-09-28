<?php

    namespace VSF\Mvc;
    use VSF\Patterns\Registry;

    class Controller
    {

        public $view;

        public function __construct()
        {
            $this->view = new View();

            $this->init();
        }

        public function init()
        {
            // Run any global things here
            // Should be overriden in a Base Controller
        }

    }