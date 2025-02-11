<?php

namespace app\core\exceptions;

use Exception;

class DatabaseException extends Exception
{
    protected $message = "Database Error";
    protected $code = 500;
}