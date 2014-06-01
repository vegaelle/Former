<?php
/**
 * Standard Field class, inherited by every final field type
 *
 * @package former
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @copyright (C) 2014 Damien Nicolas <damien@gordon.re>
 * @license AGPLv3
 */


class Former_Field_Field
{
    public $name;

    public $value;

    public $options = array();

    protected $validators = array();

    protected $filters = array();

    protected $renderer = array();

    public $errors = array();

    public $has_upload = false;

    public function __construct($name, $options = null, $validators = null,
                                  $filters = null, $renderer = null)
    {
        if($options == null) $options = array();
        if($validators == null) $validators = array();
        if($filters == null) $filters = array();
        if($renderer == null) $renderer = array();

        $this->name = $name;
        $this->options = $options;

        $this->validators = array();
        foreach($validators as $validator_name => $validator) {
            $validatorType = 'Former_Validator_'.$validator_name;
            $this->validators[$validator_name] = new $validatorType($validator);
        }

        $this->filters = array();
        foreach($filters as $filter_name => $filter) {
            $filterType = 'Former_Filter_'.$filter_name;
            $this->filters[$filter_name] = new $filterType($filter);
        }

        if(!empty($renderer)) {
            $rendererClass = 'Former_Renderer_'.$renderer['name'];
            $this->renderer = new $rendererClass($this, $renderer['options']);
        }
    }

    /**
     * runs all the validators to check the submitted data
     * @return bool the result of the validation process
     * @throws EmptyFieldException
     */
    public function validate()
    {
        foreach($this->validators as $validator_name => $validator) {
            $result = $validator->validate($this->value);
            if(!$result) {
                $this->errors[] = $validator->error;
                // if this is a blocking validator, we don’t check others.
                if($validator->blocking) {
                    return false;
                }
            }
        }
        // returns true if $this->errors is empty
        return count($this->errors) == 0;
    }

    /**
     * runs all the filters to sanitize the submitted data
     * @throws EmptyFieldException
     */
    public function filter($value)
    {
        foreach($this->filters as $filter) {
            $value = $filter->filter($value);
        }
        return $value;
    }

    public function render()
    {
        return $this->renderer->render();
    }

}
