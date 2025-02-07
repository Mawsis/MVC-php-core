<?php

namespace app\core;

abstract class BaseValidation
{
    public abstract function validate($value);
    public abstract function getErrorMessage($attribute = ""): string;
}