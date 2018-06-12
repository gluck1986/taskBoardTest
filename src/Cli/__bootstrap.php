<?php

use Symfony\Component\Console\Application;

$rootDir = dirname(dirname(__DIR__));
$webDir = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR;
$configDir = $rootDir . DIRECTORY_SEPARATOR . 'config';
$vendorDir = $rootDir . DIRECTORY_SEPARATOR . 'vendor';

require_once $vendorDir . DIRECTORY_SEPARATOR . 'autoload.php';

$config = require_once $configDir . DIRECTORY_SEPARATOR . 'config.php';
$config['services'] += [
    'root_dir' => $rootDir,
    'web_dir' => $webDir,
    'config_dir' => $configDir,
    'vendor_dir' => $vendorDir,
    'runtime_dir' => $rootDir . DIRECTORY_SEPARATOR . 'runtime',
    'log_dir' => $rootDir . DIRECTORY_SEPARATOR . 'logs',
    'src_dir' => $rootDir . DIRECTORY_SEPARATOR . 'src'
];

$container = new \Zend\ServiceManager\ServiceManager($config);

$application = new Application();

\Cli\DiAccessSingleton::init($container);

$em = $container->get(\Doctrine\ORM\EntityManager::class);
/**@var $em \Doctrine\ORM\EntityManager */

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'dialog' => new \Symfony\Component\Console\Helper\QuestionHelper(),
    'entityManager' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));
$application->setHelperSet($helperSet);
$application->addCommands([
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\LatestCommand()
]);
