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

        $titles = Title::search($query, $categoryId, $platformId);

        $allCategories = Category::findAll();
        $allPlatforms = Platform::findAll();

        $html = $templating->render('movie/index.html.php', [
            'titles' => $titles,
            'categories' => $allCategories,
            'platforms' => $allPlatforms,
            'queryParams' => [
                'q' => $query,
                'category' => $categoryId,
                'platform' => $platformId
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

}
