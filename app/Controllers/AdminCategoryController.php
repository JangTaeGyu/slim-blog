<?php
namespace App\Controllers;

use Slim\Http\{ Request, Response };
use Respect\Validation\Validator as v;
use App\Models\Category;

class AdminCategoryController extends Controller
{
    public function index(Request $request, Response $response): Response
    {
        $categories = Category::select('*')->selectRaw('ifnull(parent_id, id) as depth')->orderBy('depth', 'asc')->orderBy('id', 'asc')->get();

        return $this->view->render($response, 'admin/category/index.twig', compact('categories'));
    }

    public function store(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'name' => v::noWhitespace()->notEmpty()->length(1, 50)->categoryAvailable()->setName('카테고리')
        ]);

        $nextLink = $this->router->pathFor('blog.admin.category.index');

        if ($validation->failed()) {
            $errors = array_first($this->session->get('errors'));

            $this->session->set('fail', $errors[0]);

            return $response->withRedirect($nextLink);
        }

        $category = new Category;
        if (!empty($request->getParam('parent_id'))) {
            $category->parent_id = $request->getParam('parent_id');
        }
        $category->name = $request->getParam('name');
        $category->save();

        return $response->withRedirect($nextLink);
    }

    public function update(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'category_id' => v::noWhitespace()->intVal(),
            'name' => v::noWhitespace()->notEmpty()->length(1, 50)
        ]);

        $nextLink = $this->router->pathFor('blog.admin.category.index');

        if ($validation->failed()) {
            $errors = array_first($this->session->get('errors'));

            $this->session->set('fail', $errors[0]);

            return $response->withRedirect($nextLink);
        }

        $category = Category::find($request->getAttribute('category_id'));
        if (is_null($category)) {
            $this->session->set('fail', '카테고리 정보가 없습니다.');
        } else {
            $category->name = $request->getParam('name');
            $category->save();
        }

        return $response->withRedirect($nextLink);
    }

    public function destroy(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'category_id' => v::noWhitespace()->intVal()
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('blog.admin.category.index'));
        }

        $category = Category::find($request->getAttribute('category_id'));
        if (is_null($category)) {
            $this->session->set('fail', '카테고리 정보가 없습니다.');
        } else {
            if ($category->posts()->count() == 0) {
                if (is_null($category->parent_id)) {
                    $category->child()->delete();
                }

                $category->delete();
            } else {
                $this->session->set('fail', '글 정보가 있어 카테고리를 삭제할수 없습니다.');
            }
        }

        return $response->withRedirect($this->router->pathFor('blog.admin.category.index'));
    }
}
