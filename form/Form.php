<?php

namespace app\form;

use app\core\Model;

class Form
{
    public static function begin(string $action, string $method): Form
    {
        echo sprintf('<form action="%s" method="%s">', $action, $method);
        echo new CsrfField();
        return new Form();
    }
    public static function end(): void
    {
        echo '</form';
    }
    public function field(Model $model, $attribute)
    {
        return new InputField($model, $attribute);
    }
}
