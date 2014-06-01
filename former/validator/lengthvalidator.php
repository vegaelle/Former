<?php
/**
 * String length validator
 *
 * @package former
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @copyright (C) 2014 Damien Nicolas <damien@gordon.re>
 * @license AGPLv3
 */

class Former_Validator_LengthValidator extends Former_Validator_Validator
{

    public $blocking = false;

    public function __construct(array $options = array())
    {
        parent::__construct($options);
        $this->error = 'The value must be ';
        if(isset($options['min']) && isset($options['max'])) {
            $this->error .= 'between '.$options['min'].' and '.$options['max'].' characters.';
        }
    }


    public function validate($value)
    {
        $len = mb_strlen($value);
        if(isset($this->options['min'])) {
            if($len < $this->options['min']) return false;
        }
        if(isset($this->options['max'])) {
            if($len > $this->options['max']) return false;
        }
        return true;
    }

}

