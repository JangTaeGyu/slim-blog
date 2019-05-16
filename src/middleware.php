<?php

return function (\Slim\App $app) {
    $app->add($app->getContainer()->get('csrf'));
    $app->add(\App\Middlewares\OldInputMiddleware::class);
    $app->add(\App\Middlewares\ValidationErrorsMiddleware::class);
    $app->add(new \RunTracy\Middlewares\TracyMiddleware($app));
};
