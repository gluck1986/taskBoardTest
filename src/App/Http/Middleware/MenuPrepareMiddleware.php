<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 22.03.2018
 * Time: 10:53
 */
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Repositories\Menu\MenuRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MenuPrepareMiddleware implements MiddlewareInterface
{
    private $menuRepository;

    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
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
        $this->menuRepository->setRequest($request);

        return $handler->handle($request);
    }
}
