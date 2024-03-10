<?php

spl_autoload_register(function($className) {
    $classNameArray = explode("\\", $className);
    $classNameArray[0] = strtolower($classNameArray[1]);
    unset($classNameArray[1]);
    $className = implode("\\", $classNameArray);
    $filePath = dirname(__DIR__) . '/' . str_replace('\\', '/', $className) . '.php';

    if (file_exists($filePath)) {
        
        return require $filePath;
    }
});