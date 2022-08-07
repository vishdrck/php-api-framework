<?php

require_once __DIR__ . '/../vendor/autoload.php';

use app\kernel\Kernel;
use app\controllers\CommonController;
use app\controllers\ShopController;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$kernel = Kernel::getInstance();

$kernel->router->get('/',[CommonController::class,'index']);
$kernel->router->get('/shop-items',[ShopController::class,'getAllItems']);
$kernel->router->post('/shop-items',[ShopController::class,'updateStock']);

$kernel->run();