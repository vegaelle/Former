<?php
/**
 * Textarea field
 *
 * @package former
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @copyright (C) 2014 Damien Nicolas <damien@gordon.re>
 * @license AGPLv3
 */

class Former_Field_TextField extends Former_Field_Field
{
    public function __construct($name, $options = null, $validators = null,
                                $filters = null, $renderer = null)
    {
        parent::__construct($name, $options, $validators, $filters, $renderer);
        if($renderer == null) {
            $renderer = array('TextRenderer' => array());
        }
    }

}
