<?php
/**
 * Select field
 *
 * @package former
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @copyright (C) 2014 Damien Nicolas <damien@gordon.re>
 * @license AGPLv3
 */

class Former_Field_SelectField extends Former_Field_Field
{

    public $values = array();

    public function __construct($name, $options = null, $validators = null,
                                $filters = null, $renderer = null)
    {
        if(isset($options['values'])) {
            $values = explode('|', $options['values']);
            foreach($values as $k => $val) {
                if(strpos($val, ':') !== false) {
                    $k = substr($val, 0, strpos($val, ':'));
                    $val = substr($val, strpos($val, ':')+1);
                    $this->values[$k] = $val;
                } else {
                    $this->values[$val] = $val;
                }
            }
        } else {
            throw new InvalidArgumentException('You can’t create a SelectField without values.');
        }
        if($renderer == null) {
            $renderer = array('name' => 'selectRenderer', 'options' => array('values' => $this->values));
        }
        parent::__construct($name, $options, $validators, $filters, $renderer);
        if(!isset($this->validators['In'])) {
            $this->validators['In'] = new Former_Validator_InValidator(array('values' => array_keys($this->values)));
        }
    }

}
