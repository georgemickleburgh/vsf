<?php

    namespace VSF\Exception;

    class BasicException extends \Exception 
    {

        public function __toString() 
        {
            return $this->message;
        }

    }