<?php

namespace app\core;

#[\AllowDynamicProperties]
abstract class FormData
{
    public array $errors;
    protected array $labels = [];
    public function loadData($data, $errors = [])
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
        $this->errors = $errors;
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