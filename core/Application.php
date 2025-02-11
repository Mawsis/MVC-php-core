<?php

namespace app\core;

use app\core\facades\Handler;
use app\core\facades\Logger;
use app\providers\AppServiceProvider;
use Exception;

class Application
{
    public static string $ROOT_DIR;
    public Router $router;
    public static Application $app;
    protected array $providers = [];

    public function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;

        $this->providers = Config::get('providers');
        $this->registerProviders();

        $this->router = new Router();
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
        $this->router->request = $request;
        $this->router->response = $response;

        try {
            Logger::info('Request received', [
                'path' => $request->getPath(),
                'method' => $request->method()
            ]);
            echo $this->router->resolve();
        } catch (Exception $e) {
            Logger::error($e->getMessage(), ['exception' => $e]);
            $response->setStatusCode($e->getCode());
            echo Handler::handle($e, $response);
        }
    }
}