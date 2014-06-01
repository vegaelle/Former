<?php
/**
 * Textarea renderer
 *
 * @package former
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @copyright (C) 2014 Damien Nicolas <damien@gordon.re>
 * @license AGPLv3
 */

class Former_Renderer_TextRenderer extends Former_Renderer_Renderer
{
    protected $rendering = <<<EOT
{{input_errors}}
<label for="{{input_id}}">{{input_title}}: </label>
<textarea name="{{input_name}}"
id="{{input_id}}">{{input_value}}</textarea>
EOT;

    public function __construct($field, array $options = array())
    {
        parent::__construct($field, $options);
        if(!isset($this->options['id'])) {
            $this->options['id'] = $this->field->name;
        }
        if(!isset($this->field->options['title'])) {
            $this->options['title'] = ucfirst(str_replace(array('-', '_'),
                                                          array(' ', ' '),
                                                          $this->field->name
                                                         ));
        } else {
            $this->options['title'] = $this->field->options['title'];
        }
    }

    public function render()
    {
        $data = array();
        if(count($this->field->errors)) {
            $data['input_errors'] = '<ul class="error_list">'.PHP_EOL;
            foreach($errors as $error) {
                $data['input_errors'] .= '<li>'.$error.'</li>'.PHP_EOL;
            }
            $data['input_errors'] .= '</ul>'.PHP_EOL;
        } else {
            $data['input_errors'] = '';
        }
        $data['input_id'] = $this->options['id'];
        $data['input_name'] = $this->field->name;
        $data['input_value'] = $this->field->value;
        $data['input_title'] = $this->options['title'];
        return $this->replace_data($data);
    }
}
