<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';

$config = new \App\Service\Config();

$templating = new \App\Service\Templating();
$router = new \App\Service\Router();

$action = $_REQUEST['action'] ?? null;
$view = null;

switch ($action) {
    case 'movie-index':
    case null:
        $controller= new \App\Controller\MovieController();
        $view = $controller->indexAction($templating,$router);
        break;
    case 'movie-show':
        if (! $_REQUEST['id']) {
            break;
        }
        $controller= new \App\Controller\MovieController();
        $view = $controller->showAction($_REQUEST['id'],$templating,$router);
        break;
    case 'admin-import-csv':
        $controller = new \App\Controller\AdminController();
        $importer = new \App\Service\CSVImporter();
        $controller->importCsvAction($_FILES, $router, $importer);
        break;
    case 'admin-index':
        $controller = new \App\Controller\AdminController();
        $view = $controller->indexAction($templating,$router);
        break;
    case 'admin-add':
        $id = $_REQUEST['id']??null;
        $controller= new \App\Controller\AdminController();
        $view = $controller->addItemAction($templating,$router,$id);
        break;
    case 'category-add':
        $controller = new \App\Controller\CategoryController();
        $controller->addCategory($router, $_POST['category_name'] ?? '');
        break;
    case 'category-index':
        $controller = new \App\Controller\CategoryController();
        $view = $controller->index($templating, $router);
        break;
    case 'category-edit':
        $controller = new \App\Controller\CategoryController();
        $controller->editCategory($router, $_POST['category_id'], $_POST['category_name']);
        break;
    case 'category-delete':
        $controller = new \App\Controller\CategoryController();
        $controller->deleteCategory($router, $_POST['category_id'] ? (int)$_POST['category_id'] : null);
        break;
    case 'platform-add':
        $controller = new \App\Controller\PlatformController();
        $controller->addPlatform($router, $_POST['platform_name'] ?? '');
        break;
    case 'platform-index':
        $controller = new \App\Controller\PlatformController();
        $view = $controller->index($templating, $router);
        break;
    case 'platform-edit':
        $controller = new \App\Controller\PlatformController();
        $controller->editPlatform($router, $_POST['platform_id'], $_POST['platform_name']);
        break;
    case 'platform-delete':
        $controller = new \App\Controller\PlatformController();
        $controller->deletePlatform($router, $_POST['platform_id'] ? (int)$_POST['platform_id'] : null);
        break;
    case 'admin-add-item':
        $keys = array_flip(['title','year','kind','description','genres','platforms','id']);
        $data=array_intersect_key($_REQUEST,$keys);
        $data['id'] = $_REQUEST['id']?:null ;
        $controller = new \App\Controller\AdminController();
        $view = $controller->addAction($data, $templating, $router);
        break;
    case'delete-action':
        if (! $_REQUEST['id']) {
            break;
        }
        $movieId=$_REQUEST['id'];
        $controller = new \App\Controller\AdminController();
        $view = $controller->deleteAction($_REQUEST['id'], $router);
    case 'admin-login':
        $controller = new \App\Controller\LoginController();
        $controller->login();
        break;

    case 'admin-logout':
        $controller = new \App\Controller\LoginController();
        $controller->logout();
        break;

    default:
        $view = 'Not found';
        break;
}

if ($view) {
    echo $view;
}
