<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class BlogController extends Controller
{
    public function index(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'blog.twig');
    }
}
