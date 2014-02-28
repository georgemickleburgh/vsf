<?php

namespace VSF\Forms\Inputs;

class Radio extends Input
{

    public $template = 'RadioInput.html';
    public $options = array();

    //Add an array of options to the dropdown
    public function addOptions($array)
    {
        //Replace with a proper array merging
        foreach($array as $k=>$v) {
            $this->options[$k] = $v;
        }

        return $this;
    }

    //Get the options ready for display
    public function getOptions()
    {
        $content = '';
        foreach($this->options as $k=>$v) {
            $content .= '<label class="radio"><input type="radio" name="'.$this->getName().'" value="'.$k.'" '.(($this->value==$k)?'checked="checked"':'').' data-toggle="radio" /> ' . $v . '</label>' . PHP_EOL;
        }
        return $content;
    }

    //Check for any erroneous values
    public function validate()
    {
        //value needs to be an option index
        $errors = array();
        if(!empty($this->value)) {
            $error = true;
            foreach($this->options as $k=>$v) {
                if($k==$this->value) {
                    $error = false;
                    break;
                }
            }

            if($error === true) {
                $errors[] = 'The value you provided was not in the dropdown list';
            }
        }

        // Return errors if any exist
        if(!empty($errors)) {
            return $errors;
        }
        else {
            return false;
        }
    }

    public static function arrayToOptions($array)
    {
        $options = array();
        foreach($array as $a) {
            $key = str_replace(' ', '-', strtolower($a));
            $options[$key] = $a;
        }
        return $options;
    }

}