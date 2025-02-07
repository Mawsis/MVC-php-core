<?php

namespace app\form;

use app\core\FormData;
use app\core\Model;

abstract class BaseField
{
    abstract public function renderInput(): string;
    public FormData $data;
    public string $attribute;
    public function __construct(FormData $data, $attibute)
    {
        $this->data = $data;
        $this->attribute = $attibute;
    }
    public function __toString()
    {
        return sprintf(
            '
        <div class="mb-3">
            <label  class="form-label">%s</label>
            %s
            <div class="invalid-feedback">%s</div>
        </div>
    ',
            ucfirst($this->data->getLabel($this->attribute)),
            $this->renderInput(),
            $this->data->errors[$this->attribute] ?? ''
        );
    }
}