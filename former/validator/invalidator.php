<?php
/**
 * In (array) validator
 *
 * @package former
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @copyright (C) 2014 Damien Nicolas <damien@gordon.re>
 * @license AGPLv3
 */

class Former_Validator_InValidator extends Former_Validator_Validator
{

    public $error = 'This value does not exist.';

    public $blocking = true;

    public function validate($value)
    {
        return in_array($value, $this->options['values']);
    }

}

