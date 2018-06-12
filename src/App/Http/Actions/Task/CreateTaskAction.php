<?php
/**
 * Created by PhpStorm.
 * User: gluck
 * Date: 11.06.2018
 * Time: 17:30
 */

declare(strict_types=1);

namespace App\Http\Actions\Task;

use App\Services\Dto\TaskDto;
use App\Services\Task\CreateTaskService;
use Framework\Http\Router\Router;
use Framework\Template\TemplateRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;

class CreateTaskAction implements RequestHandlerInterface
{

    private $taskService;
    private $renderer;
    private $router;

    public function __construct(TemplateRenderer $renderer, Router $router, CreateTaskService $taskService)
    {
        $this->taskService = $taskService;
        $this->router = $router;
        $this->renderer = $renderer;
    }

    /**
     * Handle the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \ReflectionException
     * @throws \Gumlet\ImageResizeException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->getMethod() === 'POST') {
            return $this->handlePost($request);
        }
        return $this->handleGet($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \ReflectionException
     * @throws \Gumlet\ImageResizeException
     */
    private function handlePost(ServerRequestInterface $request): ResponseInterface
    {
        $request = $this->taskService->process($request);
        /** @var TaskDto $taskDto */
        $taskDto = $request->getAttribute(TaskDto::class);
        if ($taskDto->hasErrors()) {
            return $this->handleGet($request);
        }
        $url = $this->router->generate('home', []);

        return new RedirectResponse($url);

    }

    private function handleGet(ServerRequestInterface $request): ResponseInterface
    {
        $taskDto = $request->getAttribute(TaskDto::class, new TaskDto());

        return new HtmlResponse($this->renderer->render('app/task/create', ['taskDto' => $taskDto]));
    }
}
