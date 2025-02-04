<?php
namespace app\core;

use Exception;
use Psr\Log\LoggerInterface;

class Application
{
    public static string $ROOT_DIR;
    public string $userClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Database $db;
    public Session $session;
    public View $view;
    public ?UserModel $user;
    public LoggerInterface $logger;


    public Controller $controller;
    public static Application $app;
    public function __construct($rootPath, $config)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;

        $this->logger = Logger::getLogger();


        $this->userClass = $config['userClass'];
        $this->response = new Response;
        $this->request = new Request;
        $this->session = new Session;
        $this->view = new View;
        $this->router = new Router($this->request, $this->response);

        $this->db = new Database($config['db']);

        $userValue = $this->session->get('user');
        if ($userValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $userValue]);
        } else
            $this->user = null;


    }
    public function login(DbModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;

    }
    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }
    public function run()
    {
        try {
            $this->logger->info('Request received', [
                'path' => $this->request->getPath(),
                'method' => $this->request->method()
            ]);
            echo $this->router->resolve();
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error', ['exception' => $e]);
        }
    }

    public static function isGuest(): bool
    {
        return !self::$app->user;
    }
}