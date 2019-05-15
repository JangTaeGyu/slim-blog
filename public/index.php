<?php
session_start();

require dirname(__DIR__) . '/vendor/autoload.php';

$settings = require dirname(__DIR__) . '/src/settings.php';
$app = new \Slim\App($settings);

$dependencies = require dirname(__DIR__) . '/src/dependencies.php';
$dependencies($app);

$middleware = require dirname(__DIR__) . '/src/middleware.php';
$middleware($app);

$routes = require dirname(__DIR__) . '/src/routes.php';
$routes($app);

$app->run();
