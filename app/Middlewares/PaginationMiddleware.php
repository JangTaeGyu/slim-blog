<?php
namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

class PaginationMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        $view = $this->container->get('view');

        $view->getEnvironment()->addTest(new \Twig_SimpleTest('string', function ($value) {
            return is_string($value);
        }));

        \Illuminate\Pagination\Paginator::viewFactoryResolver(function() use ($view) {
            return new class($view)
            {
                private $view;
                private $template;
                private $data;

                public function __construct(\Slim\Views\Twig $view)
                {
                    $this->view = $view;
                }

                public function make(string $template, $data = null)
                {
                    $this->template = $template;
                    $this->data = $data;
                    return $this;
                }

                public function render()
                {
                    return $this->view->fetch($this->template, $this->data);
                }
            };
        });

        \Illuminate\Pagination\Paginator::currentPathResolver(function () use ($request, $next) {
            $link = $next->getPattern();
            $params = [];

            foreach ($request->getParams() as $key => $value) {
                if ($key !== 'page') {
                    $params[] = "{$key}={$value}";
                }
            }

            if (count($params) > 0) {
                $link .= "?" . implode('&', $params);
            }

            return $link;
        });

        \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($request) {
            return $request->getParam('page');
        });

        return $next($request, $response);
    }
}
