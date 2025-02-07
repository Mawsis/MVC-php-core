<?php

namespace app\core\validation;

use app\core\BaseValidation;
use app\core\QueryBuilder;

class UniqueValidation extends BaseValidation
{
    private $table;
    private $attribute;

    public function __construct($table, $attribute)
    {
        $this->table = $table;
        $this->attribute = $attribute;
    }

    public function validate($value)
    {
        $record = (new QueryBuilder($this->table))->where($this->attribute, "=", $value)->first();
        return !$record;
    }

    public function getErrorMessage($attribute = ""): string
    {
        return "$attribute has already been taken";
    }
}