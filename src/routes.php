<?php

return function (\Slim\App $app) {
    $app->get('/', \App\Controllers\BlogController::class . ':index')->setName('blog.main');

    $app->group('/admin', function ($app) {
        $app->get('/signup', \App\Controllers\AuthController::class . ':signUp')->setName('blog.admin.signup');
        $app->post('/signup', \App\Controllers\AuthController::class . ':signUpAction')->setName('blog.admin.signup.action');
        $app->get('/signin', \App\Controllers\AuthController::class . ':signIn')->setName('blog.admin.signin');
        $app->post('/signin', \App\Controllers\AuthController::class . ':signInAction')->setName('blog.admin.signin.action');
    })->add(\App\Middlewares\UnAuthMiddleware::class);

    $app->group('/admin', function ($app) {
        $app->get('/logout', \App\Controllers\AuthController::class . ':logout')->setName('blog.admin.logout');
        $app->get('/main', \App\Controllers\AdminMainController::class . ':main')->setName('blog.admin.main');
    })->add(\App\Middlewares\AuthMiddleware::class);
};
