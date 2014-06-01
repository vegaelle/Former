<?php
/**
 * Main Former class, designed to be inherited from final form classes
 *
 * @package former
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @copyright (C) 2014 Damien Nicolas <damien@gordon.re>
 * @license AGPLv3
 */


define('FORMER_PATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

/**
 * autoload function
 *
 * @param string $classname
 * @return boolean
 */
function __autoload($classname)
{
    $classname = strtolower($classname);
    
    // removing the first “former”
    if(substr($classname, 0, 7) == 'former_') {
        $classname = substr($classname, 7);
    }
    $filename = FORMER_PATH . str_replace('_', DIRECTORY_SEPARATOR, $classname) . '.php';
    if (file_exists($filename)) {
        require_once $filename;
    }

    return class_exists($classname);
}

spl_autoload_register('__autoload');


class Former
{

    /**
     * @access protected
     * @var array the array of Field objects contained in this form
     */
    protected $_fields = array();

    /**
     * @access protected
     * @var string the name of the renderer class
     */
    protected $_rendererClass = 'FormRenderer';

    /**
     * @access protected
     * @var object a Former\Renderer\FormRenderer object
     */
    protected $_renderer;

    /**
     * @access protected
     * @var array list of form errors
     */
    protected $_errors = array();

    /**
     * @access protected
     * @var string the URL or URI where to submit the form
     */
    protected $_action;

    /**
     * @access protected
     * @var string the HTTP method used by the form (get by default)
     */
    protected $_method;

    /**
     * constructs a form object, ready to be validated 
     * @param string $action optional URL or URI where to submit the form
     */
    public function __construct($action = '', $method='get')
    {
        $rendererClass = 'Former_Renderer_'.$this->_rendererClass;
        $this->_action = $action;
        $this->_method = $method;
        $this->_renderer = new $rendererClass($this);
        $this->create_from_decorators();
    }

    /**
     * renders the form object
     * @return string the generated HTML of the form
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * calls the renderer of each field, then the renderer of the entire form
     * @return string the generated HTML of the form
     */
    protected function render()
    {
        return $this->_renderer->render($this);
    }

    /**
     * validates data from an associative array (most likely $_POST) and 
     * pre-fills the form with data and/or errors.
     * @param array $data the associative array received from the form
     * @return bool is the data valid?
     */
    public function validate($data)
    {

    }

    /**
     * adds a field to the form
     * @param string $name the field’s name
     * @param string $type the field’s class name (with or without namespace)
     * @param array $options optional an associative array of field options
     * @param array $validators optional an associative array of decorators
     * @param array $filters optional an associative array of filters
     * @param array $renderer optional an associative array for the renderer
     * @return Former returns $this for fluid interface
     */
    public function add_field($name, $type, $options = null, $validators = null, 
                              $filters = null, $renderer = null)
    {
        if($options == null) $options = array();
        if($validators == null) $validators = array();
        if($filters == null) $filters = array();
        if($renderer == null) $renderer = array();

        // the property may already exist
        if(!isset($this->$name)) $this->$name = null;

        $type = 'Former_Field_'.$type;

        $this->_fields[$name] = new $type($name, $options, $validators,
                                          $filters, $renderer);

        return $this;
    }

    /**
     * reads the docblocks of the current class attributes, then creates the 
     * associated fields
     */
    public function create_from_decorators()
    {
        $reflection = new ReflectionObject($this);
        $fields = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach($fields as $field) {
            // excluding standard properties
            if($field->name[0] != '_') {
                $decorators = $this->extract_decorators($field);

                $field_type = '';
                $validators = array();
                $filters = array();
                $renderer = null;
                foreach($decorators as $decorator_name => $options) {
                    // we can now apply this decorator, depending on its type 
                    // (Field, Validator, Filter, or aliases (like Required)
                    if(substr($decorator_name, -5) == 'Field') {
                        $field_type = $decorator_name;
                        $field_options = $options;
                    } elseif(substr($decorator_name, -9) == 'Validator') {
                        $validators[$decorator_name] = $options;
                    } elseif(substr($decorator_name, -6) == 'Filter') {
                        $filters[$decorator_name] = $options;
                    } elseif(substr($decorator_name, -8) == 'Renderer') {
                        $renderer = array('name' => $decorator_name,
                                          'options' => $options);
                    } elseif($decorator_name == 'Required') {
                        $validators['Required'] = null;
                    }
                }
                if(!empty($field_type)) {
                    $this->add_field($field->name, $field_type, $field_options,
                                    $validators, $filters, $renderer);
                }
            }
        }
    }

    /**
     * parses all decorators from a property and returns them as an associative 
     * array in which values are themselves associative arrays
     * @param ReflectionProperty $field the object property to parse
     * @return array the parsed decorators
     */
    protected function extract_decorators(ReflectionProperty $field)
    {
        // TODO: add caching to this method (and avoid heavy dependencies)
        $pattern = '/(?: |\t)*\*(?: )?@([a-zA-Z]+)\((.*)\)/';
        // first matching parenthesis is the decorator name, second is 
        // the arguments, that will later be extracted
        preg_match_all($pattern,
                       $field->getDocComment(),
                       $decorators, PREG_SET_ORDER);
        $decorators_list = array();
        foreach($decorators as $decorator) {
            $decorator_name = $decorator[1];
            $fields_pattern = '/(?:(\w+)=(?:\\\'([^\\\']*)\\\'|\\"([^\\"]*)\\"|(\d+(?:\\.\d+)?)))+/';
            // first matching parenthesis is the option name, and the 
            // value may be in the 2nd, 3rd or 4th, depending on its 
            // type (respectively, single-quoted string, double-quoted 
            // string, or int)
            preg_match_all($fields_pattern,
                           $decorator[2],
                           $fields, PREG_SET_ORDER);
            $options = array();
            foreach($fields as $field) {
                if(!empty($field[2])) {
                    $field_value = $field[2];
                } elseif(!empty($field[3])) {
                    $field_value = $field[3];
                } elseif(!empty($field[4])) {
                    $field_value = (float)$field[4];
                } else {
                    $field_value = null;
                }
                $options[$field[1]] = $field_value;
            }
            $decorators_list[$decorator_name] = $options;
        }
        return $decorators_list;
    }

    public function get_fields()
    {
        return $this->_fields;
    }

    public function get_action()
    {
        return $this->_action;
    }

    public function set_action($action)
    {
        $this->_action = $action;
    }

    public function get_method()
    {
        return $this->_method;
    }

    public function set_method($method)
    {
        $this->_method = $method;
    }

    public function get_errors()
    {
        return $this->_errors;
    }

    public function add_error($error)
    {
        $this->_errors[] = $error;
    }

}
