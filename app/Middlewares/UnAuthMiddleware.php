<?php
namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

class UnAuthMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        $session = $this->container->get('session');

        if ($session->has('user')) {
            $user = $session->get('user');

            if ($user['grade'] === "A") {
                return $response->withRedirect($this->container->get('router')->pathFor('blog.admin.main'));
            } else {
                return $response->withRedirect($this->container->get('router')->pathFor('blog.main'));
            }
        }

        return $next($request, $response);
    }
}
