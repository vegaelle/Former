<?php
/**
 * Mail address validator
 *
 * @package former
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @copyright (C) 2014 Damien Nicolas <damien@gordon.re>
 * @license AGPLv3
 */

class Former_Validator_MailValidator extends Former_Validator_Validator
{

    public $error = 'This is not a valid email address.';

    public $blocking = false;

    public function validate($value)
    {
        return (bool)filter_var($value, FILTER_VALIDATE_EMAIL);
    }

}

