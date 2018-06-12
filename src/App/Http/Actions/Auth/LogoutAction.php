<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 22.03.2018
 * Time: 10:27
 */
declare(strict_types=1);

namespace App\Http\Actions\Auth;

use App\Services\Auth\WebAuthService;
use Framework\Http\Router\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;

class LogoutAction implements RequestHandlerInterface
{

    private $webAuth;
    private $router;

    public function __construct(WebAuthService $webAuth, Router $router)
    {
        $this->webAuth = $webAuth;
        $this->router = $router;
    }

    /**
     * Handle the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->webAuth->logout($request);

        return new RedirectResponse($this->router->generate('login', []), 302, $request->getHeaders());
    }
}
