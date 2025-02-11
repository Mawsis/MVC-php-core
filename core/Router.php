<?php

namespace app\core;

use app\core\exceptions\NotFoundException;
use app\core\middlewares\BaseMiddleware;

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];
    protected array $routeMiddlewares = [];
    protected array $middlewareConfig;

    public function __construct()
    {
        $this->middlewareConfig = Config::get('middlewares');
    }

    public function get(string $path, $callback, array $middlewares = [])
    {
        $this->routes['get'][$path] = $callback;
        $this->applyMiddlewares($middlewares, "get", $path);
    }

    public function post(string $path, $callback, array $middlewares = [])
    {
        $this->routes['post'][$path] = $callback;
        $this->applyMiddlewares($middlewares, "post", $path);
    }


    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();

        $params = [];
        $callback = $this->matchRoute($method, $path, $params);
        if (!$callback) {
            throw new NotFoundException();
        }

        $this->executeMiddlewares($method, is_array($callback[1]) ? $callback[0] : $path);

        if (is_array($callback[1])) {
            $callback = $callback[1];
        }
        if (is_string($callback)) {
            return View::renderView($callback);
        }

        if (is_array($callback)) {
            $controller = new $callback[0]();
            $controller->action = $callback[1];

            // Ensure correct method dependencies are resolved
            $dependencies = $this->resolveMethodDependencies($controller, $callback[1], $params ?? []);
            return call_user_func_array([$controller, $callback[1]], $dependencies);
        }

        return call_user_func_array($callback, array_merge([$this->request, $this->response], $params ?? []));
    }




    private function matchRoute(string $method, string $path, &$params = [])
    {
        if (isset($this->routes[$method][$path])) {
            return $this->routes[$method][$path];
        }

        foreach ($this->routes[$method] as $route => $callback) {
            $routePattern = preg_replace('/\{(\w+)\}/', '([^\/]+)', $route);
            if (preg_match("#^{$routePattern}$#", $path, $matches)) {
                array_shift($matches);
                $params = $matches;
                return [$route, $callback];
            }
        }

        return false;
    }

    private function applyMiddlewares(array $middlewares, $method, $path)
    {
        foreach ($middlewares as $middleware) {
            if ($middleware instanceof BaseMiddleware)
                $this->routeMiddlewares[$method][$path][] = $middleware;
            if (is_string($middleware)) {
                try {

                    $middleware = $this->middlewareConfig[$middleware];
                    $this->routeMiddlewares[$method][$path][] = new $middleware();
                } catch (\Exception $e) {
                    throw new \Exception("Middleware $middleware not found");
                }
            }
        }
    }
    private function executeMiddlewares(string $method, string $route)
    {
        $middlewares = $this->routeMiddlewares[$method][$route] ?? [];
        foreach ($middlewares as $middleware) {
            if ($middleware instanceof BaseMiddleware) {
                $middleware->execute();
            }
        }
    }
    private function resolveMethodDependencies(object $controller, string $method, array $params = []): array
    {
        $reflection = new \ReflectionMethod($controller, $method);
        $dependencies = [];

        foreach ($reflection->getParameters() as $param) {
            $type = $param->getType();
            $paramName = $param->getName();

            if ($type && !$type->isBuiltin()) {
                $className = $type->getName();

                if ($className === Request::class) {
                    // Inject the existing Request instance
                    $dependencies[] = $this->request;
                } elseif ($className === Response::class) {
                    // Inject the existing Response instance
                    $dependencies[] = $this->response;
                } elseif (is_subclass_of($className, Request::class)) {
                    // Inject custom request class (e.g., ContactRequest)
                    $instance = new $className();
                    $instance->loadData($this->request->getBody());
                    $dependencies[] = $instance;
                } else {
                    // Inject Application singletons if applicable
                    $dependencies[] = Application::$app->$className ?? null;
                }
            } else {
                // Default to request/response or null if missing
                if ($paramName === 'request') {
                    $dependencies[] = $this->request;
                } elseif ($paramName === 'response') {
                    $dependencies[] = $this->response;
                }
            }
        }
        return array_merge($dependencies, $params);
    }
}