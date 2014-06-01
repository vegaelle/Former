<?php
/**
 * Generic validator
 *
 * @package former
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @copyright (C) 2014 Damien Nicolas <damien@gordon.re>
 * @license AGPLv3
 */


class Former_Validator_Validator implements Former_Validator_ValidatorInterface
{
    public $options = array();

    public $error = 'Invalid data';

    public function __construct(array $options = array())
    {
        $this->options = $options;
    }

    public function validate($value)
    {
        return true;
    }

}
