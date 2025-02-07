<?php

namespace app\form;

class TextareaField extends BaseField
{
    public function renderInput(): string
    {
        return sprintf(
            '
            <textarea name="%s" class="form-control %s">%s</textarea>
        ',
            $this->attribute,
            $this->data->hasError($this->attribute) ? 'is-invalid' : '',
            $this->data->{$this->attribute} ?? ''
        );
    }
}