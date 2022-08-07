<?php

namespace app\kernel;

class Router
{
    private Request $request;
    private Response $response;
    protected array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function put($path, $callback)
    {
        $this->routes['put'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve(Database $database)
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        $callback = $this->routes[$method][$path] ?? false;

        if($callback ===false) {
            $this->response->setStatusCode(404);
            $response_array = array(
                'status' => 'failed',
                'message' => 'Not found'
            );
            $this->response->sendJson($response_array);
        }
        if(is_array($callback)) {
           $callback[0] = new $callback[0]();
        }

        call_user_func($callback,$this->request,$this->response,$database);
    }

}