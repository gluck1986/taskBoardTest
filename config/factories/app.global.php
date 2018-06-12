<?php

use App\Http\Actions\Special\NotFindAction;
use App\Http\Middleware\PermissionCheckMiddleware;
use App\Services\Auth\Authenticate;
use App\Services\Dto\ListSettings;
use App\Services\Dto\UploadSettings;
use Aura\Session\Session;
use Framework\Application;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Router;
use Middlewares\AuraSession;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequestFactory;

return [
    ContainerInterface::class => function (ContainerInterface $container) {
        return $container;
    },
    Application::class => function (ContainerInterface $container) {
        $app = new Application(
            $container->get(MiddlewareResolver::class),
            $container->get(Router::class),
            $container->get(NotFindAction::class)
        );

        return $app;
    },
    ServerRequestInterface::class => function () {
        return ServerRequestFactory::fromGlobals();
    },
    AuraSession::class => function () {
        $middleware = new AuraSession();
        $middleware->attribute(Session::class);

        return $middleware;
    },
    Router::class => function (ContainerInterface $container) {
        $aura = new Aura\Router\RouterContainer();

        return new AuraRouterAdapter($aura);
    },
    MiddlewareResolver::class => function (ContainerInterface $container) {
        return new MiddlewareResolver($container);
    },
    PermissionCheckMiddleware::class => function (ContainerInterface $container) {
        $permissions = $container->get('config')['auth']['permissions'];

        return new PermissionCheckMiddleware($permissions);
    },
    Authenticate::class => function (ContainerInterface $container) {
        $auth = new  Authenticate(
            $container->get('config')['auth']['adminName'],
            $container->get('config')['auth']['adminPassword']
        );

        return $auth;
    },
    UploadSettings::class => function (ContainerInterface $container) {
        $path = $container->get('web_dir') . DIRECTORY_SEPARATOR . 'upload';
        $maxWidth = $container->get('config')['upload']['maxWidth'];
        $maxHeight = $container->get('config')['upload']['maxHeight'];

        return new UploadSettings($path, $maxWidth, $maxHeight);
    },
    ListSettings::class => function (ContainerInterface $container) {
        $maxItems = $container->get('config')['listSettings']['maxItems'];
        $listSettings = new ListSettings($maxItems);

        return $listSettings;
    }
];
