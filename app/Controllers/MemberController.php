<?php
namespace App\Controllers;

use Slim\Http\{ Request, Response };
use Respect\Validation\Validator as v;
use App\Models\User;

class MemberController extends Controller
{
    public function join(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'member/join.twig');
    }

    public function joinAction(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'name' => v::noWhitespace()->notEmpty()->setName('이름'),
            'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable()->setName('이메일'),
            'password' => v::noWhitespace()->notEmpty()->length(4, 16)->setName('비밀번호'),
            'password_confirmation' => v::equals($request->getParam('password'))->setName('비밀번호 확인')
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('blog.member.join'));
        }

        User::create([
            'email' => $request->getParam('email'),
            'name' => $request->getParam('name'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT)
        ]);

        return $response->withRedirect($this->router->pathFor('blog.auth.login'));
    }
}
