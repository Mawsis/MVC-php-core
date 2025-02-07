<?php

namespace app\form;

use app\core\FormData;
use app\core\Model;

class InputField extends BaseField
{
    public string $type;
    public function __construct(FormData $data, $attibute)
    {
        parent::__construct($data, $attibute);
        $this->type = 'text';
    }
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }
    public function renderInput(): string
    {
        return sprintf(
            '<input type="%s" name="%s" value="%s" class="form-control %s" >
        ',
            $this->type,
            $this->attribute,
            $this->data->{$this->attribute} ?? '',
            $this->data->hasError($this->attribute) ? 'is-invalid' : ''
        );
    }
}