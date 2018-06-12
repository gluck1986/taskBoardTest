<?php
/**
 * Created by PhpStorm.
 * User: gluck
 * Date: 11.06.2018
 * Time: 17:32
 */

declare(strict_types=1);

namespace App\Services\Task;

use App\Entities\TaskEntity;
use App\Services\Dto\TaskDto;
use App\Services\Dto\UploadSettings;
use Doctrine\ORM\EntityManager;
use Framework\Services\FormLoader;
use Framework\Services\ValidateRule;
use Framework\Services\Validator;
use Gumlet\ImageResize;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\UploadedFile;

class CreateTaskService
{
    private $em;
    private $loader;
    private $validator;
    private $uploadSettings;

    public function __construct(
        EntityManager $entityManager,
        FormLoader $loader,
        Validator $validator,
        UploadSettings $uploadSettings
    ) {
        $this->em = $entityManager;
        $this->loader = $loader;
        $this->validator = $validator;
        $this->uploadSettings = $uploadSettings;
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @return ServerRequestInterface
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \ReflectionException
     * @throws \Gumlet\ImageResizeException
     */
    public function process(ServerRequestInterface $serverRequest): ServerRequestInterface
    {
        /** @var TaskDto $taskDto */
        $taskDto = $this->loader->load($serverRequest->getParsedBody(), TaskDto::class);

        $serverRequest = $serverRequest->withAttribute(TaskDto::class, $taskDto);

        if (count($taskDto->errors = $this->validate($taskDto)) > 0) {
            return $serverRequest;
        }
        $taskEntity = $this->makeTask($taskDto);

        $uploadedFiles = $serverRequest->getUploadedFiles();
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $uploadedFiles['image'] ?? null;
        if ($uploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = $this->moveUploadedFile($this->uploadSettings->getPath(), $uploadedFile);
            $taskEntity->setImage($filename);
        }

        $this->em->persist($taskEntity);
        $this->em->flush();

        return $serverRequest;
    }

    private function validate(TaskDto $taskDto): array
    {
        return $this->validator->validate($taskDto, [
            new ValidateRule('userName', 'string:1..255', 'Необходимо заполнить имя пользователя'),
            new ValidateRule('email', 'string:1..255', 'Необходимо заполнить email'),
            new ValidateRule('email', 'email', 'Email не валиден'),
            new ValidateRule('description', 'string:1..', 'Заполните описание'),
        ]);
    }

    private function makeTask(TaskDto $taskDto): TaskEntity
    {
        $taskEntity = new TaskEntity(
            null,
            $taskDto->userName,
            $taskDto->email,
            $taskDto->description,
            '',
            false
        );

        return $taskEntity;
    }

    /**
     * @param $directory
     * @param UploadedFile $uploadedFile
     * @return string
     * @throws \Gumlet\ImageResizeException
     */
    private function moveUploadedFile($directory, UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        $resize = new ImageResize($directory . DIRECTORY_SEPARATOR . $filename);
        $height = $resize->getSourceHeight();
        $width = $resize->getSourceWidth();
        $maxHeight = $this->uploadSettings->getMasHeight();
        $maxWidth = $this->uploadSettings->getMaxWidth();

        if ($height > $width) {
            $resize->resizeToWidth($maxWidth);
        } else {
            $resize->resizeToHeight($maxHeight);
        }
        $resize->save($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}
