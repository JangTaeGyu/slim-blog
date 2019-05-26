<?php
namespace App\Controllers;

use Slim\Http\{ Request, Response };
use App\Models\Notice;
use Respect\Validation\Validator as v;

class AdminNoticeController extends Controller
{
    public function index(Request $request, Response $response): Response
    {
        $notices = Notice::orderBy('created_at', 'desc')->paginate(10);

        return $this->view->render($response, 'admin/notice/index.twig', compact('notices'));
    }

    public function create(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'admin/notice/input.twig');
    }

    public function store(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'title' => v::notEmpty()->length(5, 100)->setName('답글'),
            'content' => v::notEmpty()->setName('본문'),
            'approved' => v::in(['0', '1'])->setName('승인')
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('blog.admin.notice.create'));
        }

        Notice::create([
            'title' => $request->getParam('title'),
            'content' => $request->getParam('content'),
            'accept_commnet' => is_null($request->getParam('accept_commnet')) ? 0 : 1,
            'approved' => $request->getParam('approved')
        ]);

        return $response->withRedirect($this->router->pathFor('blog.admin.notice.index'));
    }

    public function edit(Request $request, Response $response): Response
    {
        $notice = Notice::find($request->getAttribute('notice_id'));
        if (is_null($notice)) {
            $this->session->set('fail', '선택한 공지가 없습니다.');

            return $response->withRedirect($this->router->pathFor('blog.admin.notice.index'));
        }

        return $this->view->render($response, 'admin/notice/input.twig', compact('notice'));
    }

    public function update(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'notice_id' => v::noWhitespace()->intVal()->setName('공지 인덱스'),
            'title' => v::notEmpty()->length(5, 100)->setName('답글'),
            'content' => v::notEmpty()->setName('본문'),
            'approved' => v::in(['0', '1'])->setName('승인')
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('blog.admin.notice.edit', [
                'notice_id' => $request->getAttribute('notice_id')
            ]));
        }

        $notice = Notice::find($request->getAttribute('notice_id'));
        if (is_null($notice)) {
            $this->session->set('fail', '선택한 공지가 없습니다.');

            return $response->withRedirect($this->router->pathFor('blog.admin.notice.index'));
        }

        $notice->title = $request->getParam('title');
        $notice->content = $request->getParam('content');
        $notice->accept_commnet = is_null($request->getParam('accept_commnet')) ? 0 : 1;
        $notice->approved = $request->getParam('approved');
        $notice->save();

        return $response->withRedirect($this->router->pathFor('blog.admin.notice.index'));
    }

    public function destroy(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'notice_id' => v::noWhitespace()->intVal()->setName('공지 인덱스')
        ]);

        $nextLink = $this->router->pathFor('blog.admin.notice.index');

        if ($validation->failed()) {
            return $response->withRedirect($nextLink);
        }

        $notice = Notice::find($request->getAttribute('notice_id'));
        if (is_null($notice)) {
            $this->session->set('fail', '삭제 정보가 없습니다.');
        } else {
            $notice->delete();
        }

        return $response->withRedirect($nextLink);
    }
}
