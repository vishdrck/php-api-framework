<?php
namespace app\controllers;

use app\kernel\Request;
use app\kernel\Response;

class CommonController
{
    public function index(Request $request, Response $response) {
        $response_array = array(
            'status'=>'success',
            'message' => 'API is working'
        );
        $response->sendJson($response_array);
    }
}