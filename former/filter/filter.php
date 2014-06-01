<?php
/**
 * Generic filter
 *
 * @package former
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @copyright (C) 2014 Damien Nicolas <damien@gordon.re>
 * @license AGPLv3
 */


class Former_Filter_Filter implements Former_Filter_FilterInterface
{
    public $options = array();

    public function __construct(array $options = array())
    {
        $this->options = $options;
    }

    public function filter($value)
    {
        return $value;
    }

}
