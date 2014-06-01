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
        $this->validators = $validators;
        $this->filters = $filters;
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
        if(empty($this->value)) throw new Former_EmptyFieldException($this->name);
        foreach($this->validators as $validator) {
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
    public function filter()
    {
        if(empty($this->value)) throw new Former_EmptyFieldException($this->name);
        foreach($this->filters as $filter) {
            $this->value = $filter->filter($this->value);
        }
    }

    public function render()
    {
        if(!$this->renderer) {
            var_dump($this);
        }
        return $this->renderer->render();
    }

}
