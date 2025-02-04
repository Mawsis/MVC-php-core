<?php

namespace app\form;

use app\core\Model;

class InputField extends BaseField
{
    public string $type;
    public function __construct(Model $model, string $attribute)
    {
        parent::__construct($model, $attribute);
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
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? 'is-invalid' : ''
        );
    }
}
