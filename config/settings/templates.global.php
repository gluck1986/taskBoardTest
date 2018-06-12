<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 16.03.2018
 * Time: 16:17
 */

use App\Http\Template\Twig\Extensions\MenuItems\MenuItemsExtension;

return [
    'templates' => [
        'extension' => '.html.twig',
    ],

    'twig' => [
        'template_dir' => 'templates',
        'cache_dir' => '/runtime/cache/twig',
        'extensions' => [
            \Framework\Template\Twig\Extension\RouteExtension::class,
            MenuItemsExtension::class,
            Twig_Extensions_Extension_Text::class,
            Twig_Extensions_Extension_Intl::class,
        ],
    ],
];
