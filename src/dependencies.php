<?php

return function (\Slim\App $app) {
    $container = $app->getContainer();

    // twig
    $container['twig_profile'] = function ($container) {
        return new Twig_Profiler_Profile();
    };

    // slim/csrf
    $container['csrf'] = function ($container) {
        return new \Slim\Csrf\Guard;
    };

    // slim/twig-view
    $container['view'] = function ($container) {
        $twig = $container->get('settings')['twig'];

        $view = new \Slim\Views\Twig($twig['path'], [
            'cache' => $twig['cache']
        ]);

        $view->addExtension(new \Slim\Views\TwigExtension(
            $container->router,
            $container->request->getUri()
        ));

        $view->addExtension(new Twig_Extension_Profiler($container->get('twig_profile')));
        $view->addExtension(new Twig_Extension_Debug());

        foreach ($twig['extensions'] as $extension) {
            $view->addExtension(new $extension($container));
        }

        return $view;
    };

    // respect/validation
    $container['validator'] = function ($container) {
        return new \App\Validation\Validator($container);
    };

    // respect/validation
    \Respect\Validation\Validator::with('App\Validation\Rules', true);

    // illuminate/database
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container->get('settings')['db']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    $capsule::connection()->enableQueryLog();

    // $_SESSION
    $container['session'] = function ($container) {
        return new \App\Session\PHPSession();
    };

    // runcmf/runtracy
    \Tracy\Debugger::enable();
};
