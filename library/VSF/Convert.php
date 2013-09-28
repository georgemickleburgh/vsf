<?php

    namespace VSF;

    class Convert 
    {

        /**
         * Convert decimal number to a set base (1-62)
         * 
         * @param  int
         * @param  int
         * @return string
         */
        public static function toBase($num, $base=62) 
        {
            $index = substr("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", 0, $base);

            $out = "";

            for($t = floor(log10($num) / log10($base)); $t >= 0; $t--) {
                $a = floor($num / pow($base, $t));
                $out = $out . substr($index, $a, 1);
                $num = $num - ($a * pow($base, $t));
            }
            return $out;
        }

        /**
         * Convert a number with a set base back to decimal
         * 
         * @param  string
         * @param  int
         * @return int
         */
        public static function toDec($num, $base=62) 
        {
            $index = substr("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", 0, $base);

            $out = 0;
            $len = strlen($num) - 1;
            for ($t = 0; $t <= $len; $t++) {
                $out = $out + strpos($index, substr($num, $t, 1)) * pow($base, $len - $t);
            }
            return $out;
        }

    }