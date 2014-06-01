<?php
/**
 * Standard validator; every Former validator should implement it
 *
 * @package former
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @copyright (C) 2014 Damien Nicolas <damien@gordon.re>
 * @license AGPLv3
 */

interface Former_Validator_ValidatorInterface
{
    public function validate($value);

}
