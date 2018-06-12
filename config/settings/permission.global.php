<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 30.03.2018
 * Time: 12:48
 */
declare(strict_types=1);

return [
    'auth' => [
        'permissions' => [
            'guest' => [
                'login',
                'post.login',
                'home',
                'home.page',
                'make',
            ],
            'admin' => [
                'home',
                'home.page',
                'logout',
                'make',
                'update',
            ],
        ]
    ],
];
