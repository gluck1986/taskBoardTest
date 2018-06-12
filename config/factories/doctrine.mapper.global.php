<?php

use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;
use Psr\Container\ContainerInterface;

return [
    SimplifiedYamlDriver::class => function (ContainerInterface $container) {
        $srcPath = $container->get('src_dir');

        $mapsPath = $srcPath . DIRECTORY_SEPARATOR . 'App'
            . DIRECTORY_SEPARATOR . 'Repositories'
            . DIRECTORY_SEPARATOR . 'Doctrine'
            . DIRECTORY_SEPARATOR . 'Mapping';

        return new SimplifiedYamlDriver([
            $mapsPath => 'App\Entities',
        ]);
    },
];
