<?php

namespace app\kernel;

class Request
{
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '';

//        $path = substr($path,1,strlen($path));
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }
        return substr($path, 0, $position);
    }

    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getBody(): array
    {
        $body = [];
        if ($this->getMethod() == 'post') {
            foreach ($_POST as $key => $value){
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->getMethod() === 'put') {
            parse_str(file_get_contents("php://input"),$post_vars);
            foreach ($post_vars as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }

    public function getQueryParams(): array
    {
        $queryParams = [];

        if ($this->getMethod() === 'get') {
            foreach ($_GET as $key => $value) {
                $queryParams[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $queryParams;
    }
}