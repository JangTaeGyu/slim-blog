<?php

return function (\Slim\App $app) {
    $app->get('/', \App\Controllers\BlogController::class . ':index')->setName('blog.main');

    $app->group('/admin', function ($app) {

    })->add(\App\Middlewares\AuthMiddleware::class);
};
