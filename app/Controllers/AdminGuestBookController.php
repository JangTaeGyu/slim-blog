<?php
namespace App\Controllers;

use Slim\Http\{ Request, Response };
use App\Models\GuestBook;
use Respect\Validation\Validator as v;

class AdminGuestBookController extends Controller
{
    public function index(Request $request, Response $response): Response
    {
        $trash = $request->getUri()->getPath() === $this->router->pathFor('blog.admin.guestbook.trash');

        $guestbooks = GuestBook::where('approved', !$trash)->orderBy('created_at', 'desc')->paginate(10);

        return $this->view->render($response, 'admin/guestbook/index.twig', compact('trash', 'guestbooks'));
    }

    public function create(Request $request, Response $response): Response
    {
        $guestbook = GuestBook::find($request->getAttribute('parent_id'));
        if (is_null($guestbook)) {
            $this->session->set('fail', '방명록 정보가 없습니다.');

            return $response->withRedirect($this->router->pathFor('blog.admin.guestbook.index'));
        }

        return $this->view->render($response, 'admin/guestbook/create.twig', compact('guestbook'));
    }

    public function store(Request $request, Response $response)
    {
        $validation = $this->validator->validate($request, [
            'parent_id' => v::noWhitespace()->intVal()->setName('부모 인덱스'),
            'comment' => v::notEmpty()->setName('답글')
        ]);

        $nextLink = $this->router->pathFor('blog.admin.guestbook.index');

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('blog.admin.guestbook.create', [
                'parent_id' => $request->getAttribute('parent_id')
            ]));
        }

        $guestbook = GuestBook::find($request->getAttribute('parent_id'));
        if (is_null($guestbook)) {
            $this->session->set('fail', '방명록 정보가 없습니다.');

            return $response->withRedirect($nextLink);
        }

        GuestBook::create([
            'parent_id' => $request->getAttribute('parent_id'),
            'comment' => $request->getParam('comment')
        ]);

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
            $this->router->pathFor('blog.admin.guestbook.trash') :
            $this->router->pathFor('blog.admin.guestbook.index');

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
            $this->router->pathFor('blog.admin.guestbook.trash') :
            $this->router->pathFor('blog.admin.guestbook.index');

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
