<?php
namespace App\Controller;

use App\Model\Category;
use App\Exception\NotFoundException;
use App\Model\Title;
use App\Service\CSVImporter;
use App\Service\Router;
use App\Service\Templating;
use App\Controller\LoginController;


class AdminController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {   LoginController::checkAuth();
        $csvResult = $_SESSION['csv_import_result'] ?? null;
        unset($_SESSION['csv_import_result']);

        $query = $_GET['q'] ?? '';
        $titles = Title::search($query);

        $html = $templating->render('admin/index.html.php', [
            'titles' => $titles,
            'queryParams' => [
                'q' => $query
            ],
            'router' => $router,
            'csv_result' => $csvResult
        ]);
        return $html;
    }

    public function addItemAction(Templating $templating, Router $router, $movieId=null): string
    {   LoginController::checkAuth();
        if($movieId!==null){
            $movie=Title::find($movieId,true);
        }
        else{
            $movie=null;
        }


        $title = $movie ? 'Edit Entry' : 'Create New Entry';

        return $templating->render('admin/add_item.html.php', [
            'router' => $router,
            'title'  => $title,
            'movie'  => $movie,
        ]);
    }

    public function deleteAction(int $movieId, Router $router): ?string
    {   LoginController::checkAuth();
        $movie = Title::find($movieId);
        if (! $movie) {
            throw new NotFoundException("Missing post with id $movieId");
        }

        $movie->delete();
        $path = $router->generatePath('admin-index');
        $router->redirect($path);
        return null;
    }
    public function addAction(?array $requestMovie, Templating $templating, Router $router): ?string
    {   LoginController::checkAuth();
        if($requestMovie['id']!==null){
            $delMovie=Title::find($requestMovie['id'],true);
            $movie=Title::fromArray($requestMovie);
            $movie->save();
            foreach ($delMovie->getPlatforms() as $platform) {
                Title::removePlatformRelationship($delMovie->getId(), $platform->getId());
            }
            foreach($requestMovie['platforms'] as $platform){
                Title::addPlatformRelationship($movie->getId(), $platform);
            }
            foreach ($delMovie->getCategories() as $category) {
                Title::removeCategoryRelationship($delMovie->getId(), $category->getId());
            }
            foreach($requestMovie['genres'] as $category){
                Title::addCategoryRelationship($movie->getId(), $category);
            }
            $path = $router->generatePath('admin-index');
            $router->redirect($path);
            return null;
        }
        else {
            if ($requestMovie) {
                $movie = Title::fromArray($requestMovie);
                $movie->save();

                foreach ($requestMovie['platforms'] as $platform) {
                    Title::addPlatformRelationship($movie->getId(), $platform);
                }
                foreach ($requestMovie['genres'] as $category) {
                    Title::addCategoryRelationship($movie->getId(), $category);
                }
                $path = $router->generatePath('admin-index');
                $router->redirect($path);
                return null;
            } else {
                $movie = new Title();
            }
        }

        $title='Add Item';

        $html = $templating->render('admin/add_item.html.php', [
           'movie' => $movie,
            'router' => $router,
        ]);
        return $html;
    }


    public function importCsvAction(?array $files, Router $router, CSVImporter $importer): void
    {   LoginController::checkAuth();
        if ($files && isset($files['csv_file'])) {
            $file = $files['csv_file'];

            if ($file['error'] === UPLOAD_ERR_OK && pathinfo($file['name'], PATHINFO_EXTENSION) === 'csv') {
                $uploadDir = __DIR__ . '/../../public/uploads/csv/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $filePath = $uploadDir . uniqid('csv_') . '.csv';

                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    $result = $importer->runSingleCsvImport($filePath);
                    unlink($filePath);
                    $_SESSION['csv_import_result'] = $result;
                    $router->redirect($router->generatePath('admin-index'));
                    return;
                }
            }

            $_SESSION['csv_import_result'] = [
                'status' => 'error',
                'message' => 'Błąd uploadu pliku CSV'
            ];
        }

        $router->redirect($router->generatePath('admin-index'));
        return;
    }

}
