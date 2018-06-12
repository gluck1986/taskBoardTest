<?php

/** @var \Framework\Application $app */

use App\Http\Actions\Auth\LoginAction;
use App\Http\Actions\Auth\LogoutAction;
use App\Http\Actions\SiteIndexAction;
use App\Http\Actions\Task\CreateTaskAction;
use App\Http\Actions\Task\UpdateTaskAction;
use Framework\Application;

$app = $container->get(Application::class);

$app->get('home', '/', SiteIndexAction::class);
$app->get('home.page', '/{page}', SiteIndexAction::class, ['tokens' => ['page' => '\d+']]);
$app->any('login', '/login', LoginAction::class);
$app->post('logout', '/logout', LogoutAction::class);
$app->any('make', '/make', CreateTaskAction::class);
$app->any('update', '/update/{id}', UpdateTaskAction::class, ['tokens' => ['id' => '\d+']]);


