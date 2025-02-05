<?php

namespace app\form;

use app\core\Application;

class CsrfField
{
    public function __construct()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public function __toString(): string
    {
        return sprintf('<input type="hidden" name="_csrf" value="%s">', $_SESSION['csrf_token']);
    }
}
