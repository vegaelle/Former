<?php
/**
 * Global renderer
 *
 * @package former
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @copyright (C) 2014 Damien Nicolas <damien@gordon.re>
 * @license AGPLv3
 */

class Former_Renderer_Renderer implements Former_Renderer_RendererInterface
{

    protected $rendering = '';

    public function __construct($field, array $options = array()) {
        if($options == null) {
            $options = array();
        }
        $this->options = $options;
        $this->field = $field;
    }

    public function render()
    {
        return $this->replace_data(array());
    }

    protected function replace_data(array $data, $rendering = null)
    {
        if(!$rendering) $rendering = $this->rendering;

        foreach($data as $data_key => $data_value) {
            $rendering = str_replace('{{'.$data_key.'}}', $data_value, $rendering);
        }
        return $rendering;
    }
}
