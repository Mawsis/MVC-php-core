<?php

namespace app\core\validation;

use app\core\BaseValidation;

class EmailValidation extends BaseValidation
{
    public function validate($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function getErrorMessage($attribute = ""): string
    {
        return "The $attribute must be a valid email address";
    }
}