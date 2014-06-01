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

class Former_Field_MailField extends Former_Field_Field
{

    public function __construct($name, $options = null, $validators = null,
                                $filters = null, $renderer = null)
    {
        if($renderer == null) {
            $renderer = array('name' => 'InputRenderer', 'options' => array('type' => 'email'));
        }
        parent::__construct($name, $options, $validators, $filters, $renderer,
                            $options);
        if(!isset($this->validators['Mail'])) {
            $this->validators['Mail'] = new Former_Validator_MailValidator();
        }
    }

}
