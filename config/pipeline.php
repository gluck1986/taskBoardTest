<?php

use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\FlashInjectionMiddleware;
use App\Http\Middleware\MenuPrepareMiddleware;
use App\Http\Middleware\PermissionCheckMiddleware;
use Middlewares\AuraSession;

/** @var \Framework\Application $app */

$app->lPipe(AuraSession::class); //сессию в атрибуты (куки не используем, все в сессии, если надо продляем ей жизнь)

$app->lPipe(FlashInjectionMiddleware::class); //flash в twig окружение

$app->lPipe(AuthMiddleware::class); //добавит User::class в атрибуты если авторизован

$app->lPipe(MenuPrepareMiddleware::class);  //дать ропо меню актуальный реквест

$app->lPipe(Framework\Http\Middleware\RouteMiddleware::class);  //handler в атрибуты

$app->lPipe(PermissionCheckMiddleware::class);  //проверяем разрешения на доступ к роуту

$app->lPipe(Framework\Http\Middleware\DispatchMiddleware::class); //запускаем handler
