<?php
/**
 * Created by PhpStorm.
 * User: gluck
 * Date: 11.06.2018
 * Time: 18:43
 */
declare(strict_types=1);

namespace App\Services\Task;

use App\Entities\TaskEntity;
use App\Services\Dto\ListSettings;
use Doctrine\ORM\EntityManager;
use JasonGrimes\Paginator;
use Psr\Http\Message\ServerRequestInterface;

class IndexTaskService
{
    private $em;
    private $listSettings;

    public function __construct(EntityManager $entityManager, ListSettings $listSettings)
    {
        $this->em = $entityManager;
        $this->listSettings = $listSettings;
    }

    public function get(ServerRequestInterface $serverRequest): ServerRequestInterface
    {
        $taskRepo = $this->em->getRepository(TaskEntity::class);
        $currentPage = $serverRequest->getAttribute('page', 1);
        $totalItems = $taskRepo->count();

        $sort = $serverRequest->getQueryParams()['sort'] ?? null;
        $paginator = $this->buildPaginator($currentPage, $totalItems, $serverRequest);
        $limitOffset = $this->getLimitOffset($paginator);
        $collection = $taskRepo->allWithSortLimitOffset($sort, ...$limitOffset);
        $serverRequest = $serverRequest->withAttribute('tasks', $collection);
        $serverRequest = $serverRequest->withAttribute(Paginator::class, $paginator);
        $serverRequest = $serverRequest->withAttribute('sort', $sort);

        return $serverRequest;
    }

    private function buildPaginator($currentPage, $totalElements, ServerRequestInterface $request)
    {
        $paginator = new Paginator(
            $totalElements,
            $this->listSettings->getMaxItems(),
            $currentPage,
            '/(:num)'
            . '?'
            . http_build_query($request->getQueryParams())
        );
        return $paginator;
    }

    private function getLimitOffset(Paginator $paginator): array
    {
        $limit = $paginator->getItemsPerPage();
        $offset = $paginator->getCurrentPageFirstItem() - 1;

        return [$limit, $offset];
    }
}
