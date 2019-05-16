<?php

return [
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'twig' => [
            'path' => dirname(__DIR__) . '/templates',
            'cache' => false,
            'extensions' => [
                \App\Twig\FormExtension::class,
                \App\Twig\CsrfExtension::class,
                \App\Twig\SessionExtension::class,
            ]
        ],
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'slim-blog',
            'username' => 'root',
            'password' => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_general_ci',
            'prefix'    => '',
        ],
        'tracy' => [
            'showPhpInfoPanel' => 0,
            'showSlimRouterPanel' => 0,
            'showSlimEnvironmentPanel' => 0,
            'showSlimRequestPanel' => 1,
            'showSlimResponsePanel' => 1,
            'showSlimContainer' => 0,
            'showEloquentORMPanel' => 0,
            'showTwigPanel' => 0,
            'configs' => [
                'ConsoleNoLogin' => 0
            ]
        ]
    ]
];
