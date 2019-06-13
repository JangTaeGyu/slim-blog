<?php
namespace App\Controllers;

use Slim\Http\{ Request, Response };
use App\Models\Post;
use App\Models\Category;
use Respect\Validation\Validator as v;

class AdminPostController extends Controller
{
    use \App\Traits\UploadTrait;

    public function index(Request $request, Response $response): Response
    {
        $params = $request->getParams();

        $categories = Category::select(['id', 'parent_id', 'name'])->selectRaw('ifnull(parent_id, id) as depth')->orderBy('depth', 'asc')->orderBy('id', 'asc')->get();

        $posts = Post::categoryName()->where(function ($query) use ($params) {
            if (!empty($params['category_id'])) {
                $query = $query->where('category_id', $params['category_id']);
            }

            if (!empty($params['field']) && !empty($params['word'])) {
                return $query->where($params['field'], 'like', "%{$params['word']}%");
            }

            return $query;
        })->orderBy('created_at', 'desc')->paginate(10);

        return $this->view->render($response, 'admin/post/index.twig', compact('params', 'categories', 'posts'));
    }

    public function create(Request $request, Response $response): Response
    {
        $categories = Category::select(['id', 'parent_id', 'name'])->selectRaw('ifnull(parent_id, id) as depth')->orderBy('depth', 'asc')->orderBy('id', 'asc')->get();

        return $this->view->render($response, 'admin/post/input.twig', compact('categories'));
    }

    public function store(Request $request, Response $response)
    {
        $validation = $this->validator->validate($request, [
            'category_id' => v::intVal()->setName('카테고리'),
            'title' => v::notEmpty()->length(5, 100)->setName('제목'),
            'body' => v::notEmpty()->setName('내용'),
            'accept_commnet' => v::in(['0', '1'])->setName('권한'),
            'approved' => v::in(['0', '1'])->setName('승인')
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('blog.admin.post.create'));
        }

        $post = new Post;
        $post->category_id = $request->getParam('category_id');
        $post->title = $request->getParam('title');
        $post->body = $request->getParam('body');
        $post->accept_commnet = $request->getParam('accept_commnet');
        $post->approved = $request->getParam('approved');

        $files = $request->getUploadedFiles();
        if (array_key_exists('image', $files)) {
            $result = $this->doUpload('post/images/', $files['image'], 'image');
            if ($result) {
                $file = $this->getUpload();

                $post->image = $file['path'] . $file['name'];
            }
        }

        $post->save();

        return $response->withRedirect($this->router->pathFor('blog.admin.post.index'));
    }

    public function edit(Request $request, Response $response): Response
    {
        $categories = Category::select(['id', 'parent_id', 'name'])->selectRaw('ifnull(parent_id, id) as depth')->orderBy('depth', 'asc')->orderBy('id', 'asc')->get();

        $post = Post::find($request->getAttribute('post_id'));
        if (is_null($post)) {
            $this->session->set('fail', '선택한 글이 없습니다.');

            return $response->withRedirect($this->router->pathFor('blog.admin.post.index'));
        }

        return $this->view->render($response, 'admin/post/input.twig', compact('categories', 'post'));
    }

    public function update(Request $request, Response $response)
    {
        $validation = $this->validator->validate($request, [
            'post_id' => v::noWhitespace()->intVal()->setName('글 인덱스'),
            'category_id' => v::intVal()->setName('카테고리'),
            'title' => v::notEmpty()->length(5, 100)->setName('제목'),
            'body' => v::notEmpty()->setName('내용'),
            'accept_commnet' => v::in(['0', '1'])->setName('권한'),
            'approved' => v::in(['0', '1'])->setName('승인')
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('blog.admin.post.edit', [
                'post_id' => $request->getAttribute('post_id')
            ]));
        }

        $post = Post::find($request->getAttribute('post_id'));
        if (is_null($post)) {
            $this->session->set('fail', '선택한 글이 없습니다.');

            return $response->withRedirect($this->router->pathFor('blog.admin.post.index'));
        }

        $post->category_id = $request->getParam('category_id');
        $post->title = $request->getParam('title');
        $post->body = $request->getParam('body');
        $post->accept_commnet = $request->getParam('accept_commnet');
        $post->approved = $request->getParam('approved');

        $files = $request->getUploadedFiles();
        if (array_key_exists('image', $files)) {
            $result = $this->doUpload('post/images/', $files['image'], 'image');
            if ($result) {
                $file = $this->getUpload();

                $post->image = $file['path'] . $file['name'];
            }
        }

        $post->save();

        return $response->withRedirect($this->router->pathFor('blog.admin.post.index'));
    }

    public function updateApproved(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'post_id' => v::noWhitespace()->intVal()->setName('글 인덱스'),
            'approved' => v::in(['0', '1'])->setName('승인')
        ]);

        $nextLink = $this->router->pathFor('blog.admin.post.index');

        if ($validation->failed()) {
            $errors = array_first($this->session->get('errors'));

            $this->session->set('fail', $errors[0]);

            return $response->withRedirect($nextLink);
        }

        $post = Post::find($request->getAttribute('post_id'));
        if (is_null($post)) {
            $this->session->set('fail', '선택한 공지가 없습니다.');

            return $response->withRedirect($nextLink);
        }

        $post->approved = $request->getParam('approved');
        $post->save();

        return $response->withRedirect($nextLink);
    }

    public function destroy(Request $request, Response $response)
    {
        $validation = $this->validator->validate($request, [
            'post_id' => v::noWhitespace()->intVal()->setName('글 인덱스')
        ]);

        $nextLink = $this->router->pathFor('blog.admin.post.index');

        if ($validation->failed()) {
            $errors = array_first($this->session->get('errors'));

            $this->session->set('fail', $errors[0]);

            return $response->withRedirect($nextLink);
        }

        $post = Post::find($request->getAttribute('post_id'));
        if (is_null($post)) {
            $this->session->set('fail', '삭제 정보가 없습니다.');
        } else {
            $post->comments()->delete();
            $post->delete();
        }

        return $response->withRedirect($nextLink);
    }
}
