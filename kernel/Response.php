<?php

namespace app\kernel;

class Response
{
    public function setStatusCode(int $code)
    {
        http_response_code($code ?? 200);
    }

    public function send($responseObject)
    {
        echo $responseObject;
        exit;
    }

    public function sendJson(array $responseObject)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($responseObject);
        exit;
    }
}