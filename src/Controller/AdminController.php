<?php
namespace App\Controller;

use App\Model\Category;
use App\Model\Title;
use App\Service\CSVImporter;
use App\Service\Router;
use App\Service\Templating;

class AdminController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
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

    public function addItemAction(Templating $templating, Router $router): string
    {
        $title='Add Item';

        $html = $templating->render('admin/add_item.html.php', [
            'router' => $router,
        ]);
        return $html;
    }

    public function importCsvAction(?array $files, Router $router, CSVImporter $importer): void
    {
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
