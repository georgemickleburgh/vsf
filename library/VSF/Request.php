<?php

    namespace VSF;

    class Request 
    {

        /**
         * isPost will return true if the POST super is populated
         * with any data
         * 
         * @return boolean
         */
        public static function isPost()
        {
            if(!empty($_POST)) {
                return true;
            }
            else {
                return false;
            }
        }

        /**
         * Returns all of the current POST data as an object
         *
         * @todo make work
         * @return StdObject
         */
        public static function getPost()
        {
            if(self::isPost() || true) {
                $obj = new \StdObject();
                foreach($_GET as $k=>$v) {
                    $obj->$k = $v;
                }

                return $obj;
            }
            else {
                return false;
            }
        }

    }