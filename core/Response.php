<?php
namespace app\core;
class Response
{
    public function setStatusCode($code)
    {
        if (is_int($code)) {
            http_response_code($code);
        } else {
            http_response_code(500);
        }
    }
    public function redirect(string $path)
    {
        header("Location: $path");
    }
}