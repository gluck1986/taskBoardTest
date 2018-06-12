<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 18.03.2018
 * Time: 23:08
 */

namespace App\Http\Middleware;

use App\Services\Auth\Authenticate;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class AuthMiddleware
 * @package App\Common\Http\Middleware
 */
class AuthMiddleware implements MiddlewareInterface
{
    private $authenticate;

    public function __construct(Authenticate $authenticate)
    {
        $this->authenticate = $authenticate;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $this->authenticate->authBySession($request);

        if ($user) {
            $request = $request->withAttribute('admin', true);
        }

        return $handler->handle($request);
    }
}
