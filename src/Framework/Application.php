<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 14.03.2018
 * Time: 18:49
 */

namespace Framework;

use Framework\Http\Pipeline\MiddlewarePipe;
use Framework\Http\Pipeline\MiddlewarePipeInterface;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\RouteData;
use Framework\Http\Router\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Application implements MiddlewarePipeInterface
{
    private $resolver;
    private $router;
    private $default;
    private $pipeline;

    public function __construct(MiddlewareResolver $resolver, Router $router, RequestHandlerInterface $default)
    {
        $this->resolver = $resolver;
        $this->router = $router;
        $this->default = $default;
        $this->pipeline = new MiddlewarePipe();
    }

    public function lPipe($middleware)
    {
        $this->pipe($this->resolver->resolve($middleware));
    }

    public function pipe($middleware)
    {
        $this->pipeline->pipe($middleware);
    }

    public function any($name, $path, $handler, array $options = [])
    {
        $this->route($name, $path, $handler, [], $options);
    }

    private function route($name, $path, $handler, array $methods, array $options = [])
    {
        $this->router->addRoute(new RouteData($name, $path, $handler, $methods, $options));
    }

    public function get($name, $path, $handler, array $options = [])
    {
        $this->route($name, $path, $handler, ['GET'], $options);
    }

    public function post($name, $path, $handler, array $options = [])
    {
        $this->route($name, $path, $handler, ['POST'], $options);
    }

    public function put($name, $path, $handler, array $options = [])
    {
        $this->route($name, $path, $handler, ['PUT'], $options);
    }

    public function patch($name, $path, $handler, array $options = [])
    {
        $this->route($name, $path, $handler, ['PATCH'], $options);
    }

    public function delete($name, $path, $handler, array $options = [])
    {
        $this->route($name, $path, $handler, ['DELETE'], $options);
    }

    public function run(ServerRequestInterface $request)
    {
        return $this->process($request, $this->default);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->pipeline->process($request, $handler);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->pipeline->handle($request);
    }
}
