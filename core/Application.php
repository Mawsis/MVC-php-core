<?php

namespace app\core;

use app\core\facades\Handler;
use app\core\facades\Logger;
use app\core\middlewares\CorsMiddleware;
use app\providers\AppServiceProvider;
use Exception;

class Application
{
    public static string $ROOT_DIR;
    public static Application $app;
    protected array $providers = [];

    public function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;

        (new CorsMiddleware)->execute();
        $this->providers = Config::get('providers');
        $this->registerProviders();
    }

    public function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $provider = new $provider();
            $provider->register();
        }
    }

    public function run()
    {
        $response = new Response();
        $request = new Request();
        $router = Container::make('route');
        $router->request = $request;
        $router->response = $response;

        try {
            Logger::info('Request received', [
                'path' => $request->getPath(),
                'method' => $request->method()
            ]);
            echo $router->resolve();
        } catch (Exception $e) {
            Logger::error($e->getMessage(), ['exception' => $e]);
            $response->setStatusCode($e->getCode());
            echo Handler::handle($e, $response);
        }
    }
}