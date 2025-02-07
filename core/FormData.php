<?php

namespace app\core;

abstract class FormData
{
    public array $errors;
    protected array $labels = [];
    public function loadData($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
    public function getLabel($attribute): string
    {
        return $this->labels[$attribute] ?? $attribute;
    }
    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }
}