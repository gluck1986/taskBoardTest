<?php

namespace Framework\Http\Middleware;

use Framework\Http\Pipeline\Exception\UnknownMiddlewareTypeException;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\Result;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DispatchMiddleware implements MiddlewareInterface
{
    private $resolver;

    public function __construct(MiddlewareResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Result $result */
        if (!$result = $request->getAttribute(Result::class)) {
            return $handler->handle($request);
        }
        $action = $this->resolver->resolve($result->getHandler());
        if ($action instanceof MiddlewareInterface) {
            return $action->process($request, $handler);
        } elseif ($action instanceof RequestHandlerInterface) {
            return $action->handle($request);
        }

        throw new UnknownMiddlewareTypeException(get_class($action));
    }
}
