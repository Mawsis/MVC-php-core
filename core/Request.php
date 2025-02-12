<?php

namespace app\core;

use app\core\exceptions\ValidationException;
use Exception;

#[\AllowDynamicProperties]
class Request
{
    public array $errors = [];
    public array $validated = [];
    protected array $rules = [];
    protected array $validationConfig;

    public function __construct()
    {
        $this->validationConfig = require Application::$ROOT_DIR . '/config/validations.php';
        $this->loadData($this->getBody());
    }

    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        return $position ? substr($path, 0, $position) : $path;
    }

    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet(): bool
    {
        return $this->method() === 'get';
    }

    public function isPost(): bool
    {
        return $this->method() === 'post';
    }

    public function getBody(): array
    {
        $body = [];
        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->isPost()) {
            if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
                $jsonInput = file_get_contents('php://input');
                $decodedJson = json_decode($jsonInput, true);
                if (is_array($decodedJson)) {
                    return $decodedJson;
                }
            }
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }
    protected function isAuthorized(): bool
    {
        return true;
    }
    public function validate(array $rules = [])
    {
        $rules = $rules ?: $this->rules;
        $body = $this->getBody();
        foreach ($rules as $attribute => $r) {
            foreach ($r as $rule) {
                if ($rule instanceof BaseValidation) {
                    if ($rule->validate($body[$attribute])) {
                        $this->validated[$attribute] = $body[$attribute];
                    } else {
                        $this->errors[$attribute] = $rule->getErrorMessage($attribute);
                    }
                }
                if (is_string($rule)) {
                    $stripped = explode(':', $rule);
                    $rule = array_shift(array: $stripped);
                    $params = $stripped;
                    $rule = $this->validationConfig[$rule];
                    $validation = $params ? new $rule(...$params) : new $rule();
                    if ($validation->validate($body[$attribute])) {
                        $this->validated[$attribute] = $body[$attribute];
                    } else {
                        $this->errors[$attribute] = $validation->getErrorMessage($attribute);
                    }
                }
            }
        }
        return empty($this->errors);
    }

    public function validated()
    {
        $this->validate();
        return $this->validated;
    }

    public function loadData(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function addError(string $attribute, string $message): void
    {
        $this->errors[$attribute] = $message;
    }
}