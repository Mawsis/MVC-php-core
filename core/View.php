<?php

namespace app\core;

class View
{
    public static function renderView($view, $params = [], $layout = 'main')
    {
        $layoutContent = static::layoutContent($layout);
        $viewContent = static::renderOnlyView($view, $params);
        return str_replace("{{content}}", $viewContent, $layoutContent);
    }

    protected static function layoutContent($layout)
    {
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        return ob_get_clean();
    }

    protected static function renderOnlyView($view, $params = [])
    {
        extract($params);
        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }
}