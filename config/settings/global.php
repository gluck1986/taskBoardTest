<?php

return [
    'auth' => [
        'adminName' => 'admin', //week
        'adminPassword' => '123',
    ],
    'debug' => true,
    'db' => [
        'driver' => 'pdo_mysql',
        'user' => 'root',
        'password' => 'root',
        'host' => 'localhost',
        'dbname' => 'test',
        'driverOptions' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        ]
    ],
    'doctrine' => [
        'proxyDir' => 'doctrine' . DIRECTORY_SEPARATOR . 'proxy',
        'cacheDir' => 'doctrine' . DIRECTORY_SEPARATOR . 'cache',
        'proxyNameSpace' => 'Proxies',
    ],
    'upload' => [
        'maxWidth' => 320,
        'maxHeight' => 240
    ],
    'listSettings' => [
        'maxItems' => 3
    ]
];
