<?php

session_start();

require __dir__.'/../src/autoload/autoload.php';

use Source\Middlewares\RouterMiddleware;

$router = new RouterMiddleware();

$router->execute();
