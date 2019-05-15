<?php
namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

class AuthMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        return $next($request, $response);
    }
}
