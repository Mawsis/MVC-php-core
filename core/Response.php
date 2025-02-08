<?php

namespace app\core;

class Response
{

    protected array $headers = [];
    protected array $variables = [];
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

        echo json_encode([
            'success' => $statusCode < 400,
            'status' => $statusCode,
            'data' => $data,
        ], JSON_PRETTY_PRINT);
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
    public function assign(string $key, $value)
    {
        $this->variables[$key] = $value;
    }
    public function render(string $view, $params = [])
    {
        foreach ($this->variables as $key => $value) {
            $params[$key] = $value;
        }
        return View::renderView($view, $params);
    }
}