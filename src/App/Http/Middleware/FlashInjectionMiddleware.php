<?php
/**
 * Created by PhpStorm.
 * User: gluck
 * Date: 10.05.2018
 * Time: 22:05
 */
declare(strict_types=1);

namespace App\Http\Middleware;

use Aura\Session\Session;
use Framework\Template\TemplateRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FlashInjectionMiddleware implements MiddlewareInterface
{

    private $renderer;

    public function __construct(TemplateRenderer $renderer)
    {
        $this->renderer = $renderer;
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
        /** @var Session $session */
        $session = $request->getAttribute(Session::class);
        $segment = $session->getSegment('app');
        $flashInfo = $segment->getFlash('info');
        $flashErr = $segment->getFlash('err');
        $this->renderer->addGlobal('flashInfo', $flashInfo);
        $this->renderer->addGlobal('flashErr', $flashErr);

        return $handler->handle($request);
    }
}
