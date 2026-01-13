<?php
session_start();
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';

$config = new \App\Service\Config();

$templating = new \App\Service\Templating();
$router = new \App\Service\Router();

$action = $_REQUEST['action'] ?? null;

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
        $view = $controller->importCsvAction($_FILES, $router);
        break;
    case 'admin-index':
        $controller= new \App\Controller\AdminController();
        $view = $controller->indexAction($templating,$router);
        break;
    case 'admin-add':
        $controller= new \App\Controller\AdminController();
        $view = $controller->addItemAction($templating,$router);
        break;
    default:
        $view = 'Not found';
        break;
}

if ($view) {
    echo $view;
}
