<?php

namespace app\core\middlewares;

use app\core\Config;

class CorsMiddleware extends BaseMiddleware
{
    public function execute()
    {
        $corsConfig = Config::get('cors');

        $allowedOrigins = $corsConfig['allowed_origins'];
        $allowedMethods = implode(", ", $corsConfig['allowed_methods']);
        $allowedHeaders = implode(", ", $corsConfig['allowed_headers']);

        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

        header("Access-Control-Allow-Methods: $allowedMethods");
        header("Access-Control-Allow-Headers: $allowedHeaders");

        if (in_array('*', $allowedOrigins) || in_array($origin, $allowedOrigins)) {
            header("Access-Control-Allow-Origin: $origin");
        } else {
            http_response_code(403);
            echo json_encode([
                "error" => "CORS policy: This origin is not allowed."
            ]);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }
}