<?php

return function (\Slim\App $app) {
    $app->get('/', \App\Controllers\BlogController::class . ':index')->setName('blog.main');

    $app->get('/admin/signup', \App\Controllers\AuthController::class . ':signUp')->setName('blog.admin.signup');
    $app->post('/admin/signup', \App\Controllers\AuthController::class . ':signUpAction')->setName('blog.admin.signup.action');
    $app->get('/admin/signin', \App\Controllers\AuthController::class . ':signIn')->setName('blog.admin.signin');

    $app->group('/admin', function ($app) {

    })->add(\App\Middlewares\AuthMiddleware::class);
};
