<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;
use App\Models\Category;

class AdminCategoryController extends Controller
{
    public function index(Request $request, Response $response): Response
    {
        $categories = Category::all();

        return $this->view->render($response, 'admin/category/index.twig', ['categories' => $categories]);
    }

    public function store(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'category_name' => v::noWhitespace()->notEmpty()->length(1, 50)->categoryAvailable()
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('blog.admin.category.index'));
        }

        Category::create([
            'name' => $request->getParam('category_name')
        ]);

        return $response->withRedirect($this->router->pathFor('blog.admin.category.index'));
    }

    public function update(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'category_id' => v::noWhitespace()->intVal(),
            'category_name' => v::noWhitespace()->notEmpty()->length(1, 50)
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('blog.admin.category.index'));
        }

        $category = Category::find($request->getParam('category_id'));
        if (is_null($category)) {
            $this->session->set('fail', '카테고리 정보가 없습니다.');
        } else {
            $category->name = $request->getParam('category_name');
            $category->save();
        }

        return $response->withRedirect($this->router->pathFor('blog.admin.category.index'));
    }

    public function destroy(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'category_id' => v::noWhitespace()->intVal()
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('blog.admin.category.index'));
        }

        $category = Category::find($request->getParam('category_id'));
        if (is_null($category)) {
            $this->session->set('fail', '카테고리 정보가 없습니다.');
        } else {
            if ($category->posts()->count() == 0) {
                $category->delete();
            } else {
                $this->session->set('fail', '포스트 정보가 있어 카테고리를 삭제할수 없습니다.');
            }
        }

        return $response->withRedirect($this->router->pathFor('blog.admin.category.index'));
    }
}
