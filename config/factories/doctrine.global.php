<?php

use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;
use Psr\Container\ContainerInterface;

return [
    Cache::class => function (ContainerInterface $container) {
        if ($container->get('config')['debug']) {
            return new \Doctrine\Common\Cache\ArrayCache();
        } else {
            $configData = $container->get('config')['doctrine'];
            $runtimePath = $container->get('runtime_dir');
            $cachePath = $runtimePath . DIRECTORY_SEPARATOR . $configData['cacheDir'];

            return new \Doctrine\Common\Cache\FilesystemCache($cachePath);
        }
    },

    Configuration::class => function (ContainerInterface $container) {
        $configData = $container->get('config')['doctrine'];
        $runtimePath = $container->get('runtime_dir');
        $proxyPath = $runtimePath . DIRECTORY_SEPARATOR . $configData['proxyDir'];
        $configuration = new Configuration();
        $configuration->setProxyDir($proxyPath);
        $configuration->setProxyNamespace($configData['proxyNameSpace']);
        $configuration->setMetadataCacheImpl($container->get(Cache::class));
        $configuration->setQueryCacheImpl($container->get(Cache::class));
        $metaDataDriver = $container->get(SimplifiedYamlDriver::class);
        /**@var $metaDataDriver SimplifiedYamlDriver */
        $configuration->setMetadataDriverImpl($metaDataDriver);

        return $configuration;
    },
    EntityManager::class => function (ContainerInterface $container) {
        $configData = $container->get('config')['db'];
        $configuration = $container->get(Configuration::class);
        /**@var $configuration Configuration */
        return EntityManager::create($configData, $configuration);
    },
];
