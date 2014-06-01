<?php
/**
 * Text Input field
 *
 * @package former
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @copyright (C) 2014 Damien Nicolas <damien@gordon.re>
 * @license AGPLv3
 */

class Former_Field_InputField extends Former_Field_Field
{

    public function __construct($name, $options = null, $validators = null,
                                $filters = null, $renderer = null)
    {
        if($renderer == null) {
            $renderer = array('name' => 'inputRenderer', 'options' => array());
        }
        parent::__construct($name, $options, $validators, $filters, $renderer);
        if(!isset($this->options['type'])) {
            $this->options['type'] = 'text';
        }
        if($this->options['type'] == 'file') $this->has_upload = true;
    }

}
