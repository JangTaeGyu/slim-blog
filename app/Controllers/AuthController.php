<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;
use App\Models\User;

class AuthController extends Controller
{
    public function signUp(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'admin/auth/signup.twig');
    }

    public function signUpAction(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'name' => v::noWhitespace()->notEmpty(),
            'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'password' => v::noWhitespace()->notEmpty()->length(4, 16),
            'password_confirmation' => v::equals($request->getParam('password'))
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('blog.admin.signup'));
        }

        User::create([
            'email' => $request->getParam('email'),
            'name' => $request->getParam('name'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT)
        ]);

        return $response->withRedirect($this->router->pathFor('blog.admin.signin'));
    }

    public function signIn(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'admin/auth/signin.twig');
    }
}
