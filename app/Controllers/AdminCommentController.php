<?php
namespace App\Controllers;

use Slim\Http\{ Request, Response };
use App\Models\Comment;
use Respect\Validation\Validator as v;

class AdminCommentController extends Controller
{
    public function index(Request $request, Response $response): Response
    {
        $params = $request->getParams();

        $trash = $request->getUri()->getPath() === $this->router->pathFor('blog.admin.comment.trash');

        $comments = Comment::targetTitle()->where('approved', !$trash)->where(function ($query) use ($params) {
            if (!empty($params['field']) && !empty($params['word'])) {
                return $query->where($params['field'], 'like', "%{$params['word']}%");
            }

            return $query;
        })->orderBy('created_at', 'desc')->paginate(10);

        return $this->view->render($response, 'admin/comment/index.twig', compact('params', 'trash', 'comments'));
    }

    public function store(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'parent_id' => v::noWhitespace()->intVal()->setName('부모 인덱스'),
            'comment' => v::notEmpty()->setName('답글')
        ]);

        $nextLink = $this->router->pathFor('blog.admin.comment.index');

        if ($validation->failed()) {
            $errors = array_first($this->session->get('errors'));

            $this->session->set('fail', $errors[0]);

            return $response->withRedirect($nextLink);
        }

        $parent = Comment::find($request->getParam('parent_id'));

        $comment = new Comment;
        $comment->target = $parent->target;
        $comment->target_id = $parent->target_id;
        $comment->comment = $request->getParam('comment');

        $parent->child()->save($comment);

        return $response->withRedirect($nextLink);
    }

    public function update(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'comment_id' => v::noWhitespace()->intVal()->setName('댓글 인덱스'),
            'approved' => v::in(['0', '1'])->setName('승인여부'),
            'trash' => v::in(['0', '1'])->setName('경로')
        ]);

        $nextLink = $request->getParam('trash') ?
            $this->router->pathFor('blog.admin.comment.trash') : $this->router->pathFor('blog.admin.comment.index');

        if ($validation->failed()) {
            return $response->withRedirect($nextLink);
        }

        $comment = Comment::find($request->getAttribute('comment_id'));
        if (is_null($comment)) {
            $this->session->set('fail', '수정 정보가 없습니다.');

            return $response->withRedirect($nextLink);
        } else {
            if (is_null($comment->parent_id)) {
                $comment->child()->update(['approved' => $request->getParam('approved')]);
            }

            $comment->approved = $request->getParam('approved');
            $comment->save();
        }

        return $response->withRedirect($nextLink);
    }

    public function destroy(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'comment_id' => v::noWhitespace()->intVal()->setName('댓글 인덱스'),
            'trash' => v::in(['0', '1'])->setName('경로')
        ]);

        $nextLink = $request->getParam('trash') ?
            $this->router->pathFor('blog.admin.comment.trash') : $this->router->pathFor('blog.admin.comment.index');

        if ($validation->failed()) {
            return $response->withRedirect($nextLink);
        }

        $comment = Comment::find($request->getAttribute('comment_id'));
        if (is_null($comment)) {
            $this->session->set('fail', '삭제 정보가 없습니다.');
        } else {
            if (is_null($comment->parent_id)) {
                $comment->child()->delete();
            }

            $comment->delete();
        }

        return $response->withRedirect($nextLink);
    }
}
