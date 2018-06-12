<?php
/**
 * Created by PhpStorm.
 * User: gluck
 * Date: 10.06.2018
 * Time: 14:26
 */

declare(strict_types=1);

namespace App\Http\Actions;

use App\Services\Task\IndexTaskService;
use Framework\Template\TemplateRenderer;
use JasonGrimes\Paginator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;

class SiteIndexAction implements RequestHandlerInterface
{

    private $renderer;
    private $taskService;

    public function __construct(TemplateRenderer $renderer, IndexTaskService $taskService)
    {
        $this->renderer = $renderer;
        $this->taskService = $taskService;
    }

    /**
     * Handle the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $request = $this->taskService->get($request);
        $tasks = $request->getAttribute('tasks');
        $paginator = $request->getAttribute(Paginator::class);
        $sort = $request->getAttribute('sort');
        $canUpdate = $request->getAttribute('admin');

        return new HtmlResponse($this->renderer->render('app/task/index', [
            'tasks' => $tasks,
            'paginator' => $paginator,
            'sort' => $sort,
            'canUpdate' => $canUpdate
        ]));
    }
}
