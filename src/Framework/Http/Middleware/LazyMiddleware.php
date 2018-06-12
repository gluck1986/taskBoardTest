<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 15.03.2018
 * Time: 15:00
 */

namespace Framework\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LazyMiddleware implements MiddlewareInterface
{
    /**
     * @var callable
     */
    private $factory;
    /**
     * @var MiddlewareInterface
     */
    private $app;

    public function __construct(callable $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $app = $this->createApp();
        if ($app instanceof RequestHandlerInterface) {
            return $app->handle($request);
        } else {
            return $app->process($request, $handler);
        }
    }

    /**
     * @return MiddlewareInterface
     */
    private function createApp()
    {
        $this->app = $this->app ?: call_user_func($this->factory);
        return $this->app;
    }
}
