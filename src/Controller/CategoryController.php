<?php

namespace App\Controller;

use App\Model\Category;
use App\Service\Router;
use App\Service\Templating;

class CategoryController
{
    public function index(Templating $templating, Router $router): ?string
    {
        $categoryResult = $_SESSION['add_category_result'] ?? null;
        unset($_SESSION['add_category_result']);

        $categories = Category::findAll();
        return $templating->render('admin/category/index.html.php', [
            'categories' => $categories,
            'router' => $router,
            'add_category_result' => $categoryResult,
        ]);
    }

    public function addCategory(Router $router, string $categoryName)
    {
        if (empty($categoryName)) {
            $_SESSION['add_category_result'] = [
                'status' => 'error',
                'message' => 'Category name cannot be empty'
            ];
            $router->redirect($router->generatePath('category-index'));
            return;
        }
        $existingCategory = Category::findByName($categoryName);
        if ($existingCategory) {
            $_SESSION['add_category_result'] = [
                'status' => 'error',
                'message' => 'Category already exists!'
            ];
            $router->redirect($router->generatePath('category-index'));
            return;
        }

        $newCategory = new Category();
        $newCategory->setName($categoryName);
        $newCategory->save();
        $_SESSION['add_category_result'] = [
            'status' => 'success',
            'message' => 'Category added successfully',
        ];
        $router->redirect($router->generatePath('category-index'));
        return;
    }

    public function deleteCategory(Router $router, ?int $categoryId): void
    {
        if (empty($categoryId)) {
            //todo message
            $router->redirect($router->generatePath('category-index'));
            return;
        }
        $category = Category::find($categoryId);
        $category->delete();
        $router->redirect($router->generatePath('category-index'));
        //todo message
        return;
    }

    public function editCategory(Router $router, ?int $categoryId, ?string $categoryName): void
    {
        if (empty($categoryId) || empty($categoryName)) {
            $router->redirect($router->generatePath('category-index'));
            return;
        }

        $category = Category::find($categoryId);
        $category->setName($categoryName);
        $category->save();
        $router->redirect($router->generatePath('category-index'));
        return;
    }
}