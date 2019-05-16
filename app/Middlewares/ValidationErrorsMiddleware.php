<?php
namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

class ValidationErrorsMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        $session = $this->container->get('session');

        $this->container->get('view')->getEnvironment()->addGlobal('errors', $session->get('errors'));

        $session->delete('errors');

        return $next($request, $response);
    }
}
