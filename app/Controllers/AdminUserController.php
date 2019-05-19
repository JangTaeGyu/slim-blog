<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\User;
use Respect\Validation\Validator as v;

class AdminUserController extends Controller
{
    public function index(Request $request, Response $response): Response
    {
        $users = User::orderBy('created_at', 'desc')->paginate(5);

        return $this->view->render($response, 'admin/user/index.twig', compact('users'));
    }

    public function edit(Request $request, Response $response): Response
    {
        $user = User::find($request->getAttribute('user_id'));
        if (is_null($user)) {
            $this->session->set('fail', '회원 정보가 없습니다.');

            return $response->withRedirect($this->router->pathFor('blog.admin.user.index'));
        }

        return $this->view->render($response, 'admin/user/edit.twig', compact('user'));
    }

    public function update(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'user_id' => v::noWhitespace()->intVal(),
            'approved' => v::in(['0', '1']),
            'password_confirmation' => v::equals($request->getParam('password'))
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('blog.admin.user.edit', ['user_id' => $request->getAttribute('user_id')]));
        }

        $user = User::find($request->getAttribute('user_id'));
        if (is_null($user)) {
            $this->session->set('fail', '회원 정보가 없습니다.');

            return $response->withRedirect($this->router->pathFor('blog.admin.user.index'));
        }

        if ($request->getParam('password') !== '') {
            $user->password = password_hash($request->getParam('password'), PASSWORD_DEFAULT);
        }

        $user->approved = $request->getParam('approved');
        $user->save();

        return $response->withRedirect($this->router->pathFor('blog.admin.user.index'));
    }

    public function destroy(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'user_id' => v::noWhitespace()->intVal()
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('blog.admin.user.index'));
        }

        $user = User::find($request->getAttribute('user_id'));
        if (is_null($user)) {
            $this->session->set('fail', '회원 정보가 없습니다.');
        } else {
            $user->delete();
        }

        return $response->withRedirect($this->router->pathFor('blog.admin.user.index'));
    }
}
