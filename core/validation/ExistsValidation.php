<?php

namespace app\core\validation;

use app\core\BaseValidation;
use app\core\QueryBuilder;

class ExistsValidation extends BaseValidation
{
    private $table;
    private $attribute;

    public function __construct($table, $attribute)
    {
        $this->table = $table;
        $this->attribute = $attribute;
    }

    public function validate($value): bool
    {
        $record = (new QueryBuilder($this->table))->where($this->attribute, "=", $value)->first();
        return isset($record);
    }

    public function getErrorMessage($attribute = ""): string
    {
        return "$attribute does not exist";
    }
}