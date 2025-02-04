<?php
namespace app\core;

use app\core\exceptions\NotFoundException;

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get(string $path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post(string $path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();

        $callback = $this->matchRoute($method, $path, $params);

        if (!$callback) {
            throw new NotFoundException;
        }

        if (is_string($callback)) {
            return Application::$app->view->renderView($callback);
        }

        if (is_array($callback)) {
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];

            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }

            return call_user_func_array([$controller, $callback[1]], array_merge([$this->request, $this->response], $params));
        }

        return call_user_func_array($callback, array_merge([$this->request, $this->response], $params));
    }

    private function matchRoute(string $method, string $path, &$params = [])
    {
        // Exact match first
        if (isset($this->routes[$method][$path])) {
            return $this->routes[$method][$path];
        }

        // Check for dynamic routes
        foreach ($this->routes[$method] as $route => $callback) {
            $routePattern = preg_replace('/\{(\w+)\}/', '([^\/]+)', $route);
            if (preg_match("#^{$routePattern}$#", $path, $matches)) {
                array_shift($matches); // Remove full match
                $params = $matches;
                return $callback;
            }
        }

        return false;
    }
}