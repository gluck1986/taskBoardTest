<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 30.03.2018
 * Time: 13:00
 */
declare(strict_types=1);

namespace App\Http\Middleware;

use Framework\Http\Router\Result;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\EmptyResponse;

/**
 * Class PermissionCheckMiddleware
 * @package App\Http\Middleware
 */
class PermissionCheckMiddleware implements MiddlewareInterface
{

    /**
     * @var array
     */
    private $permissions;

    /**
     * PermissionCheckMiddleware constructor.
     * @param array $permissions
     */
    public function __construct(array $permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $result = $request->getAttribute(Result::class);
        if (!$result || !($result instanceof Result)) {
            return $handler->handle($request);
        }
        $roleName = $request->getAttribute('admin') ? 'admin' : 'guest';
        /**@var $result Result */

        $rolePermissions = $this->permissions[$roleName];

        if (in_array($result->getName(), $rolePermissions)) {
            return $handler->handle($request);
        } else {
            return new EmptyResponse(403);
        }
    }
}
