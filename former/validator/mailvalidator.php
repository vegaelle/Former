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

class Former_Validator_MailValidator implements Former_Validator_ValidatorInterface
{
    public function validate($value)
    {
        return (bool)filter_var($value, FILTER_VALIDATE_EMAIL);
    }

}

