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

    public function signInAction(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email(),
            'password' => v::noWhitespace()->notEmpty()->length(4, 16),
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('blog.admin.signin'));
        }

        $errors = [];

        $user = User::where('email', $request->getParam('email'))->first();
        if (is_null($user)) {
            $errors = array_merge($errors, ['email' => ['일치하는 회원이 없습니다.']]);
        } else {
            if (!password_verify($request->getParam('password'), $user->password)) {
                $errors = array_merge($errors, ['password' => ['비밀번호가 일치하지 않습니다.']]);
            }
        }

        if (count($errors) > 0) {
            $this->session->set('errors', $errors);

            return $response->withRedirect($this->router->pathFor('blog.admin.signin'));
        }

        $this->session->set('user', [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name
        ]);

        return $response->withRedirect($this->router->pathFor('blog.admin.main'));
    }

    public function logout(Request $request, Response $response): Response
    {
        if ($this->session->has('user')) {
            $this->session->delete('user');
        }

        return $response->withRedirect($this->router->pathFor('blog.admin.signin'));
    }
}
