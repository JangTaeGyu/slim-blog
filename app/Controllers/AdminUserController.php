<?php
namespace App\Controllers;

use Slim\Http\{ Request, Response };
use App\Models\User;
use Respect\Validation\Validator as v;

class AdminUserController extends Controller
{
    public function index(Request $request, Response $response): Response
    {
        $params = $request->getParams();

        $users = User::where(function ($query) use ($params) {
            if (!empty($params['field']) && !empty($params['word'])) {
                return $query->where($params['field'], 'like', "%{$params['word']}%");
            }

            return $query;
        })->orderBy('created_at', 'desc')->paginate(10);

        return $this->view->render($response, 'admin/user/index.twig', compact('params', 'users'));
    }

    public function edit(Request $request, Response $response): Response
    {
        $user = User::find($request->getAttribute('user_id'));
        if (is_null($user)) {
            $this->session->set('fail', '회원 정보가 없습니다.');

            return $response->withRedirect($this->router->pathFor('blog.admin.user.index'));
        }

        return $this->view->render($response, 'admin/user/input.twig', compact('user'));
    }

    public function update(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'user_id' => v::noWhitespace()->intVal()->setName('회원 인덱스'),
            'name' => v::noWhitespace()->notEmpty()->setName('이름'),
            'email' => v::noWhitespace()->notEmpty()->email()->setName('이메일'),
            'grade' => v::in(['N', 'A'])->setName('등급'),
            'approved' => v::in(['0', '1'])->setName('승인')
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('blog.admin.user.edit', [
                'user_id' => $request->getAttribute('user_id')
            ]));
        }

        $user = User::find($request->getAttribute('user_id'));
        if (is_null($user)) {
            $this->session->set('fail', '회원 정보가 없습니다.');

            return $response->withRedirect($this->router->pathFor('blog.admin.user.index'));
        }

        $user->name = $request->getParam('name');
        $user->email = $request->getParam('email');
        $user->grade = $request->getParam('grade');
        $user->approved = $request->getParam('approved');
        $user->save();

        return $response->withRedirect($this->router->pathFor('blog.admin.user.index'));
    }

    public function updateApproved(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'user_id' => v::noWhitespace()->intVal()->setName('회원 인덱스'),
            'approved' => v::in(['0', '1'])->setName('승인')
        ]);

        $nextLink = $this->router->pathFor('blog.admin.user.index');

        if ($validation->failed()) {
            $errors = array_first($this->session->get('errors'));

            $this->session->set('fail', $errors[0]);

            return $response->withRedirect($nextLink);
        }

        $user = User::find($request->getAttribute('user_id'));
        if (is_null($user)) {
            $this->session->set('fail', '선택한 회원이 없습니다.');

            return $response->withRedirect($nextLink);
        }

        $user->approved = $request->getParam('approved');
        $user->save();

        return $response->withRedirect($nextLink);
    }

    public function destroy(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'user_id' => v::noWhitespace()->intVal()
        ]);

        $nextLink = $this->router->pathFor('blog.admin.user.index');

        if ($validation->failed()) {
            $errors = array_first($this->session->get('errors'));

            $this->session->set('fail', $errors[0]);

            return $response->withRedirect($nextLink);
        }

        $user = User::find($request->getAttribute('user_id'));
        if (is_null($user)) {
            $this->session->set('fail', '회원 정보가 없습니다.');
        } else {
            $user->posts()->delete();
            $user->notices()->delete();
            $user->comments()->delete();
            $user->guestbooks()->delete();
            $user->delete();
        }

        return $response->withRedirect($nextLink);
    }
}
