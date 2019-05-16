<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class AdminMainController extends Controller
{
    public function main(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'admin/main.twig');
    }
}
