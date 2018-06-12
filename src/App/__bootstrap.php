<?php

$rootDir = dirname(dirname(__DIR__));
$webDir = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR;
$configDir = $rootDir . DIRECTORY_SEPARATOR . 'config';
$vendorDir = $rootDir . DIRECTORY_SEPARATOR . 'vendor';
require_once $vendorDir . DIRECTORY_SEPARATOR . 'autoload.php';
$config = require_once $configDir . DIRECTORY_SEPARATOR . 'config.php';
$config['services'] += [
    'root_dir' => $rootDir,
    'web_dir' => $webDir,
    'config_dir' => $configDir,
    'vendor_dir' => $vendorDir,
    'runtime_dir' => $rootDir . DIRECTORY_SEPARATOR . 'runtime',
    'log_dir' => $rootDir . DIRECTORY_SEPARATOR . 'logs',
    'src_dir' => $rootDir . DIRECTORY_SEPARATOR . 'src'
];

$container = new \Zend\ServiceManager\ServiceManager($config);
$app = $container->get(\Framework\Application::class);

require_once $configDir . DIRECTORY_SEPARATOR . 'pipeline.php';
require_once $configDir . DIRECTORY_SEPARATOR . 'routes.php';

return $container;
