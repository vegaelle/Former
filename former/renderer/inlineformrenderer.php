<?php
/**
 * Inline form renderer
 *
 * @package former
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @copyright (C) 2014 Damien Nicolas <damien@gordon.re>
 * @license AGPLv3
 */

class Former_Renderer_InlineFormRenderer extends Former_Renderer_Renderer
{
        protected $rendering = <<<'EOT'
<form method="{{form_method}}" action="{{form_action}}"{{has_upload}}>
    {{form_errors}}
    {{form_contents}}
</form>
EOT;
    public function render()
    {
        $data = array();
        if(count($errors = $this->field->get_errors())) {
            $data['form_errors'] = '<ul class="error_list">'.PHP_EOL;
            foreach($errors as $error) {
                $data['form_errors'] .= '<li>'.$error.'</li>'.PHP_EOL;
            }
            $data['form_errors'] .= '</ul>'.PHP_EOL;
        } else {
            $data['form_errors'] = '';
        }
        $data['form_contents'] = '';
        $data['has_upload'] = '';
        foreach($this->field->get_fields() as $field) {
            if($field->has_upload && empty($has_upload)) {
                $data['has_upload'] = ' enctype="multipart/form-data"';
            }
            $data['form_contents'] .= '<span class="field_row">'
                                     .$field->render().'</span>'.PHP_EOL;
        }
        $data['form_method'] = $this->field->get_method();
        $data['form_action'] = $this->field->get_action();
        return $this->replace_data($data);
    }
}
