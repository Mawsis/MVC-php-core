<?php

namespace app\core\exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    protected $message = "Unauthorized access";
    protected $code = 401;
}