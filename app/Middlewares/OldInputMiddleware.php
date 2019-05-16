<?php
namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

class OldInputMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        $session = $this->container->get('session');

        $this->container->get('view')->getEnvironment()->addGlobal('old', $session->get('old'));

        $session->set('old', $request->getParams());

        return $next($request, $response);
    }
}
