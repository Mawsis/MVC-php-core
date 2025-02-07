<?php

namespace app\core;

abstract class BaseValidation
{
    public abstract function validate($value): bool;
    public abstract function getErrorMessage($attribute = ""): string;
}