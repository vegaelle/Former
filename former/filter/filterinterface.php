<?php
/**
 * Standard filter; every Former filter should implement it
 *
 * @package former
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @copyright (C) 2014 Damien Nicolas <damien@gordon.re>
 * @license AGPLv3
 */

interface Former_Filter_FilterInterface
{
    public function filter($value);

}
