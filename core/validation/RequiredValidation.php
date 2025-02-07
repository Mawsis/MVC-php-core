<?php

namespace app\core\validation;

use app\core\BaseValidation;

class RequiredValidation extends BaseValidation
{
    public function validate($value): bool
    {
        //validate for being required number or string or date whatever throw validation error otherwise
        return $value !== null && trim($value) !== '';
    }

    public function getErrorMessage($attribute = ""): string
    {
        return "The $attribute is required";
    }
}