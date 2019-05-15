<?php

return function (\Slim\App $app) {
    $app->add(new \RunTracy\Middlewares\TracyMiddleware($app));
};
