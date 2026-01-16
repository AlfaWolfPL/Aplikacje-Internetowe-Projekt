<?php
namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Category;
use App\Model\Platform;
use App\Model\Title;
use App\Service\Router;
use App\Service\Templating;

class MovieController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
        $query = $_GET['q'] ?? '';
        $categoryId = !empty($_GET['category']) ? (int)$_GET['category'] : null;
        $platformId = !empty($_GET['platform']) ? (int)$_GET['platform'] : null;
        $kind = !empty($_GET['kind']) ? $_GET['kind'] : null;

        $titles = Title::search($query, $categoryId, $platformId, $kind);

        $allCategories = Category::findAll();
        $allPlatforms = Platform::findAll();

        $html = $templating->render('movie/index.html.php', [
            'titles' => $titles,
            'categories' => $allCategories,
            'platforms' => $allPlatforms,
            'queryParams' => [
                'q' => $query,
                'category' => $categoryId,
                'platform' => $platformId,
                'kind' => $kind,
            ],
            'router' => $router,
        ]);
        return $html;
    }


    public function showAction(int $movieId, Templating $templating, Router $router): ?string
    {
        $movie = Title::find($movieId,true);
        if (! $movie) {
            throw new NotFoundException("Missing movie with id $movieId");
        }

        $html = $templating->render('movie/show.html.php', [
            'movie' => $movie,
            'router' => $router,
        ]);
        return $html;
    }

    public function autocompleteAction(): void
    {
        if (ob_get_length()) ob_clean();

        header('Content-Type: application/json; charset=utf-8');

        $query = $_GET['q'] ?? '';

        if (strlen($query) < 2) {
            echo json_encode(['suggestion' => null]);
            exit;
        }

        $title = \App\Model\Title::findOneByTitleStart($query);

        if ($title && stripos($title, $query) === 0) {
            $suggestion = substr($title, strlen($query));
            echo json_encode([
                'full_title' => $title,
                'suggestion_part' => $suggestion
            ]);
        } else {
            echo json_encode(['suggestion' => null]);
        }

        exit;
    }

}
