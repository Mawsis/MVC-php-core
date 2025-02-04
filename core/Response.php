<?php
namespace app\core;

class Response
{
    public function setStatusCode(int $code)
    {
        if (!headers_sent()) {
            http_response_code($code);
        }
    }

    public function redirect(string $path)
    {
        if (!headers_sent()) {
            header("Location: $path");
            exit;
        }
    }
}