<?php

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/src/database/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => 'db',
            'name' => 'sandbox',
            'user' => 'dbuser',
            'pass' => '12345678',
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => 'db',
            'name' => 'sandbox',
            'user' => 'dbuser',
            'pass' => '12345678',
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'testing' => [
            'adapter' => 'mysql',
            'host' => 'db',
            'name' => 'sandbox',
            'user' => 'dbuser',
            'pass' => '12345678',
            'port' => '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
