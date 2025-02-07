<?php

use app\core\validation\RequiredValidation;

return [
    "required" => RequiredValidation::class,
    "min" => app\core\validation\MinValidation::class,
    "max" => app\core\validation\MaxValidation::class,
    "email" => app\core\validation\EmailValidation::class,
    "unique" => app\core\validation\UniqueValidation::class,
];