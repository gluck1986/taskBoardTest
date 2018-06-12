<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 09.04.2018
 * Time: 21:34
 */

declare(strict_types=1);

use App\Common\Entities\Request\RequestEntity;
use App\Common\Repositories\Catalog\RequestEntityRepository;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

return [
    RequestEntityRepository::class => function (ContainerInterface $container) {
        return $container->get(EntityManager::class)->getRepository(RequestEntity::class);
    }

];
