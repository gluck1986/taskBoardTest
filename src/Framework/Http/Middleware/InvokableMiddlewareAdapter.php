<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 16.03.2018
 * Time: 17:01
 */

namespace Framework\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;

class InvokableMiddlewareAdapter implements MiddlewareInterface
{
    private $middleware;

    public function __construct(callable $middleware)
    {
        $this->middleware = $middleware;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return ($this->middleware)(
            $request,
            new Response(),
            function (ServerRequestInterface $request) use ($handler) {
                return $handler->handle($request);
            }
        );
    }
}
