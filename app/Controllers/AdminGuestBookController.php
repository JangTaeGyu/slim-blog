<?php
namespace App\Controllers;

use Slim\Http\{ Request, Response };
use App\Models\GuestBook;
use Respect\Validation\Validator as v;

class AdminGuestBookController extends Controller
{
    public function index(Request $request, Response $response): Response
    {
        $params = $request->getParams();

        $trash = $request->getUri()->getPath() === $this->router->pathFor('blog.admin.guestbook.trash');

        $guestbooks = GuestBook::where('approved', !$trash)->where(function ($query) use ($params) {
            if (!empty($params['field']) && !empty($params['word'])) {
                return $query->where($params['field'], 'like', "%{$params['word']}%");
            }

            return $query;
        })->orderBy('created_at', 'desc')->paginate(10);

        return $this->view->render($response, 'admin/guestbook/index.twig', compact('params', 'trash', 'guestbooks'));
    }

    public function store(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'parent_id' => v::noWhitespace()->intVal()->setName('부모 인덱스'),
            'comment' => v::notEmpty()->setName('답글')
        ]);

        $nextLink = $this->router->pathFor('blog.admin.guestbook.index');

        if ($validation->failed()) {
            $errors = array_first($this->session->get('errors'));

            $this->session->set('fail', $errors[0]);

            return $response->withRedirect($nextLink);
        }

        $parent = GuestBook::find($request->getParam('parent_id'));

        $guestbook = new GuestBook;
        $guestbook->comment = $request->getParam('comment');

        $parent->child()->save($guestbook);

        return $response->withRedirect($nextLink);
    }

    public function update(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'guestbook_id' => v::noWhitespace()->intVal()->setName('방명록 인덱스'),
            'approved' => v::in(['0', '1'])->setName('승인여부'),
            'trash' => v::in(['0', '1'])->setName('경로')
        ]);

        $nextLink = $request->getParam('trash') ?
            $this->router->pathFor('blog.admin.guestbook.trash') : $this->router->pathFor('blog.admin.guestbook.index');

        if ($validation->failed()) {
            return $response->withRedirect($nextLink);
        }

        $guestbook = GuestBook::find($request->getAttribute('guestbook_id'));
        if (is_null($guestbook)) {
            $this->session->set('fail', '수정 정보가 없습니다.');

            return $response->withRedirect($nextLink);
        } else {
            if (is_null($guestbook->parent_id)) {
                $guestbook->child()->update(['approved' => $request->getParam('approved')]);
            }

            $guestbook->approved = $request->getParam('approved');
            $guestbook->save();
        }

        return $response->withRedirect($nextLink);
    }

    public function destroy(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'guestbook_id' => v::noWhitespace()->intVal()->setName('방명록 인덱스'),
            'trash' => v::in(['0', '1'])->setName('경로')
        ]);

        $nextLink = $request->getParam('trash') ?
            $this->router->pathFor('blog.admin.guestbook.trash') : $this->router->pathFor('blog.admin.guestbook.index');

        if ($validation->failed()) {
            return $response->withRedirect($nextLink);
        }

        $guestbook = GuestBook::find($request->getAttribute('guestbook_id'));
        if (is_null($guestbook)) {
            $this->session->set('fail', '삭제 정보가 없습니다.');
        } else {
            if (is_null($guestbook->parent_id)) {
                $guestbook->child()->delete();
            }

            $guestbook->delete();
        }

        return $response->withRedirect($nextLink);
    }
}
