<?php

session_start();

spl_autoload_register(function($className) {
    $classNameArray = explode("\\", $className);
    $classNameArray[0] = strtolower($classNameArray[1]);
    unset($classNameArray[1]);
    $className = implode("\\", $classNameArray);
    $filePath = dirname(__DIR__) . '/src/' . str_replace('\\', '/', $className) . '.php';

    if (file_exists($filePath)) {
        return require $filePath;
    }
});

use Source\Middlewares\RouterMiddleware;

$router = new RouterMiddleware();

$router->execute();
