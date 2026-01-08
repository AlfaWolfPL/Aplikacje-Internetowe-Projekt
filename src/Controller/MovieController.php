<?php
namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Title;
use App\Service\Router;
use App\Service\Templating;

class MovieController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
        //Wyswietlanie filmow na stronie glownej (dodac "q" w findAll)
        $titles = Title::findAll(true);

        $html = $templating->render('movie/index.html.php', [
            'titles' => $titles,
            'router' => $router,
        ]);
        return $html;
    }


    public function showAction(int $movieId, Templating $templating, Router $router): ?string
    {
        $movie = Title::find($movieId);
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
