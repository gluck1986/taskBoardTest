<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 10.06.2018
 * Time: 15:57
 */

namespace App\Http\Actions\Auth;

use App\Services\Auth\WebAuthService;
use App\Services\Dto\LoginDto;
use Framework\Http\Router\Router;
use Framework\Template\TemplateRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;

class LoginAction implements RequestHandlerInterface
{

    private $renderer;
    private $router;
    private $webAuthService;

    public function __construct(TemplateRenderer $renderer, WebAuthService $webAuthService, Router $router)
    {
        $this->renderer = $renderer;
        $this->webAuthService = $webAuthService;
        $this->router = $router;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \ReflectionException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->getMethod() === 'POST') {
            return $this->handlePost($request);
        } else {
            return $this->handleGet($request);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \ReflectionException
     */
    public function handlePost(ServerRequestInterface $request): ResponseInterface
    {
        $request = $this->webAuthService->authenticate($request);
        /** @var LoginDto $loginDto */
        $loginDto = $request->getAttribute(LoginDto::class);
        if ($loginDto->hasErrors()) {
            return $this->handleGet($request);
        }
        $path = $this->router->generate('home', []);

        return new RedirectResponse($path);
    }

    public function handleGet(ServerRequestInterface $request): ResponseInterface
    {
        $loginDto = $request->getAttribute(LoginDto::class, new LoginDto());
        /**@var $loginDto LoginDto */

        return new HtmlResponse($this->renderer->render('app/auth/login', [
            'loginDto' => $loginDto
        ]));
    }
}
