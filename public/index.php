<?php
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
    default:
        $view = 'Not found';
        break;
}

if ($view) {
    echo $view;
}
