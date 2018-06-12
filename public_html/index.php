<?php

use Framework\Application;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\SapiEmitter;

/**@var $app Application*/
/**@var $emitter \Zend\Diactoros\Response\EmitterInterface*/
setlocale(LC_ALL, 'ru_RU', 'ru_RU.UTF-8', 'ru', 'russian');
$container = require_once dirname(__DIR__) . '/src/App/__bootstrap.php';
$emitter = $container->get(SapiEmitter::class);
$response =  $app->run($container->get(ServerRequestInterface::class));
$emitter->emit($response);
