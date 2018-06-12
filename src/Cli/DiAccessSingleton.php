<?php
/**
 * Created by PhpStorm.
 * User: gluck
 * Date: 01.06.2018
 * Time: 17:04
 */
declare(strict_types=1);

namespace Cli;

use Psr\Container\ContainerInterface;

class DiAccessSingleton
{

    private static $container;

    public static function init(ContainerInterface $container)
    {
        self::$container = $container;
    }

    public static function instance(): ContainerInterface
    {
        return self::$container;
    }
}
