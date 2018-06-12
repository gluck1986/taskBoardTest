<?php
/**
 * Created by PhpStorm.
 * User: gluck
 * Date: 12.06.2018
 * Time: 15:17
 */
declare(strict_types=1);

namespace App\Services\Task;

use App\Entities\TaskEntity;
use App\Services\Dto\TaskDto;
use Doctrine\ORM\EntityManager;
use Framework\Services\FormLoader;
use Framework\Services\ValidateRule;
use Framework\Services\Validator;
use Psr\Http\Message\ServerRequestInterface;

class UpdateTaskService
{
    private $em;
    private $loader;
    private $validator;
    private $task;

    public function __construct(
        EntityManager $entityManager,
        FormLoader $loader,
        Validator $validator
    ) {
        $this->em = $entityManager;
        $this->loader = $loader;
        $this->validator = $validator;
        $this->task = null;
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @return ServerRequestInterface
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function getDto(ServerRequestInterface $serverRequest): ServerRequestInterface
    {
        if ($serverRequest->getAttribute(TaskDto::class)) {
            return $serverRequest;
        }
        $id = $serverRequest->getAttribute('id');
        $taskDto = new TaskDto();
        $serverRequest = $serverRequest->withAttribute(TaskDto::class, $taskDto);
        if (!$id) {
            return $serverRequest;
        }
        $taskEntity = $this->getTask($id);
        if (is_null($taskEntity)) {
            return $serverRequest;
        }
        $this->populateDto($taskEntity, $taskDto);

        return $serverRequest;
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @return ServerRequestInterface
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @throws \ReflectionException
     */
    public function process(ServerRequestInterface $serverRequest): ServerRequestInterface
    {
        /** @var TaskDto $taskDto */
        $taskDto = new TaskDto();
        $taskDto->id = $serverRequest->getAttribute('id');
        $taskEntity = $this->getTask($taskDto->id);
        if ($taskEntity) {
            $this->populateDto($taskEntity, $taskDto);
        }
        $taskDto = $this->loader->load($serverRequest->getParsedBody(), $taskDto, ['description', 'resolved']);
        $serverRequest = $serverRequest->withAttribute(TaskDto::class, $taskDto);
        if (count($taskDto->errors = $this->validate($taskDto)) > 0) {
            return $serverRequest;
        }

        $taskEntity->setDescription($taskDto->description);
        $taskEntity->setResolved((bool)$taskDto->resolved);
        $this->em->persist($taskEntity);
        $this->em->flush();

        return $serverRequest;
    }

    private function validate(TaskDto $taskDto): array
    {
        return $this->validator->validate($taskDto, [
            new ValidateRule('description', 'string:1..', 'Заполните описание'),
            new ValidateRule('id', 'int:1..', 'Невалидный идентификатор задачи'),
            new ValidateRule('id', '', '', function($id) {
                $task = $this->getTask($id);
                if (!$task) {
                    return 'Не найдена задача';
                }
                return null;
            })
        ]);
    }

    /**
     * @param $id
     * @return TaskEntity|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    private function getTask($id)
    {
        if (!is_null($this->task)) {
            return $this->task;
        }
        $this->task = $this->em->find(TaskEntity::class, $id);

        return $this->task;
    }

    private function populateDto(TaskEntity $taskEntity, TaskDto $taskDto): TaskDto
    {
        $taskDto->id = $taskEntity->getId();
        $taskDto->userName = $taskEntity->getUserName();
        $taskDto->email = $taskEntity->getEmail();
        $taskDto->description = $taskEntity->getDescription();
        $taskDto->image = $taskEntity->getImage();
        $taskDto->resolved = $taskEntity->getResolved();

        return $taskDto;
    }
}
