<?php

namespace app\core;

class Response
{
    protected array $headers = [];

    public function setStatusCode(int $code)
    {
        if (!headers_sent()) {
            http_response_code($code);
        }
    }

    public function setHeader(string $key, string $value)
    {
        if (!headers_sent()) {
            header("$key: $value");
        }
        $this->headers[$key] = $value;
    }

    public function json($data, int $statusCode = 200)
    {
        $this->setStatusCode($statusCode);
        $this->setHeader("Content-Type", "application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    public function send(string $content, string $contentType = "text/html", int $statusCode = 200)
    {
        $this->setStatusCode($statusCode);
        $this->setHeader("Content-Type", $contentType);
        echo $content;
        exit;
    }

    public function redirect(string $path)
    {
        if (!headers_sent()) {
            header("Location: $path");
            exit;
        }
    }
}