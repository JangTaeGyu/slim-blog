<?php
namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

class AuthMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        $session = $this->container->get('session');

        if ($session->has('user')) {
            return $next($request, $response);
        }

        $session->set('fail', '로그인 정보가 없습니다.');

        return $response->withRedirect($this->container->get('router')->pathFor('blog.auth.login'));
    }
}
