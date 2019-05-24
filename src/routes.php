<?php

return function (\Slim\App $app) {
    $app->get('/', \App\Controllers\BlogController::class . ':index')->setName('blog.main');

    $app->group('/admin', function (\Slim\App $app) {
        $app->get('/signup', \App\Controllers\AuthController::class . ':signUp')->setName('blog.admin.signup');
        $app->post('/signup', \App\Controllers\AuthController::class . ':signUpAction')->setName('blog.admin.signup.action');
        $app->get('/signin', \App\Controllers\AuthController::class . ':signIn')->setName('blog.admin.signin');
        $app->post('/signin', \App\Controllers\AuthController::class . ':signInAction')->setName('blog.admin.signin.action');
    })->add(\App\Middlewares\UnAuthMiddleware::class);

    $app->group('/admin', function (\Slim\App $app) {
        $app->get('/logout', \App\Controllers\AuthController::class . ':logout')->setName('blog.admin.logout');
        $app->get('/main', \App\Controllers\AdminMainController::class . ':main')->setName('blog.admin.main');

        $app->get('/categories', \App\Controllers\AdminCategoryController::class . ':index')->setName('blog.admin.category.index');
        $app->post('/categories', \App\Controllers\AdminCategoryController::class . ':store')->setName('blog.admin.category.store');
        $app->put('/categories/{category_id:[0-9]+}', \App\Controllers\AdminCategoryController::class . ':update')->setName('blog.admin.category.update');
        $app->delete('/categories/{category_id:[0-9]+}', \App\Controllers\AdminCategoryController::class . ':destroy')->setName('blog.admin.category.destroy');

        $app->get('/users', \App\Controllers\AdminUserController::class . ':index')->add(\App\Middlewares\PaginationMiddleware::class)->setName('blog.admin.user.index');
        $app->get('/users/{user_id:[0-9]+}/edit', \App\Controllers\AdminUserController::class . ':edit')->setName('blog.admin.user.edit');
        $app->put('/users/{user_id:[0-9]+}', \App\Controllers\AdminUserController::class . ':update')->setName('blog.admin.user.update');
        $app->delete('/users/{user_id:[0-9]+}', \App\Controllers\AdminUserController::class . ':destroy')->setName('blog.admin.user.destroy');

        $app->get('/guestbooks', \App\Controllers\AdminGuestBookController::class . ':index')->add(\App\Middlewares\PaginationMiddleware::class)->setName('blog.admin.guestbook.index');
        $app->get('/guestbooks/trash', \App\Controllers\AdminGuestBookController::class . ':index')->add(\App\Middlewares\PaginationMiddleware::class)->setName('blog.admin.guestbook.trash');
        $app->get('/guestbooks/{parent_id:[0-9]+}/create', \App\Controllers\AdminGuestBookController::class . ':create')->setName('blog.admin.guestbook.create');
        $app->post('/guestbooks/{parent_id:[0-9]+}', \App\Controllers\AdminGuestBookController::class . ':store')->setName('blog.admin.guestbook.store');
        $app->put('/guestbooks/{guestbook_id:[0-9]+}', \App\Controllers\AdminGuestBookController::class . ':update')->setName('blog.admin.guestbook.update');
        $app->delete('/guestbooks/{guestbook_id:[0-9]+}', \App\Controllers\AdminGuestBookController::class . ':destroy')->setName('blog.admin.guestbook.destroy');
    })->add(\App\Middlewares\AuthMiddleware::class);
};
