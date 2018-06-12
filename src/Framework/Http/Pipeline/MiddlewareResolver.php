<?php

namespace Framework\Http\Pipeline;

use Framework\Http\Middleware\LazyMiddleware;
use Framework\Http\Pipeline\Exception\UnknownMiddlewareTypeException;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareResolver
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve($handler): MiddlewareInterface
    {
        if (\is_array($handler)) {
            return $this->createPipe($handler);
        }

        if (\is_string($handler) && $this->container->has($handler)) {
//            if ($handler instanceof RequestHandlerInterface) {
//                return  $this->container->get($handler);
//            }
            return new LazyMiddleware(
                function () use ($handler) {
                    return $this->container->get($handler);
                }
            );
        }

        if ($handler instanceof MiddlewareInterface || $handler instanceof RequestHandlerInterface) {
            return $handler;
        }

        throw new UnknownMiddlewareTypeException($handler);
    }

    private function createPipe(array $handlers): MiddlewareInterface
    {
        $pipeline = new MiddlewarePipe();
        foreach ($handlers as $handler) {
            $pipeline->pipe($this->resolve($handler));
        }
        return $pipeline;
    }
}
