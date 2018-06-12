<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 15.03.2018
 * Time: 22:18
 */

namespace App\Http\Actions\Special;

use Framework\Template\TemplateRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;

class NotFindAction implements RequestHandlerInterface
{

    private $template;

    public function __construct(TemplateRenderer $template)
    {
        $this->template = $template;
    }

    /**
     * Handle the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->template->render('app/error/404', [
            'request' => $request,
        ]), 404);
    }
}
