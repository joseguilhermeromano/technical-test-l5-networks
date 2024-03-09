<?php 

$webRoutes = [
    "GET" => [
        "/" => "IndexController@index",
    ],
];

$apiRoutes = [
    "GET" => [
        "/api" => "MonitorController@index",
        "/api/ramais" => "MonitorController@index",
    ]
];