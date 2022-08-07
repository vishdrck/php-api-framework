<?php

namespace app\kernel;

class Kernel
{
    public Router $router;
    public Request $request;
    public Response $response;
    public Database $database;
    public array $config;
    private static $instance = null;

    private function __construct()
    {
        $this->config = $this->getConfig();
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        // $this->routes = new Routes($this->router);
        $this->database = new Database($this->config['db']);
    }

    private function getConfig(): array
    {
        return array(
            'db' => [
                'dsn' => $_ENV['DB_DSN'],
                'username' => $_ENV['DB_USERNAME'],
                'password' => $_ENV['DB_PASSWORD']
            ]
        );
    }

    public static function getInstance(): ?Kernel
    {
        if (self::$instance == null) {
            self::$instance = new Kernel();
        }

        return self::$instance;
    }

    public function run()
    {
        // $this->routes->createRoutes();
        $this->router->resolve($this->database);
        // $this->routes->router->resolve();
    }
}