<?php
namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Movie; //TODO: Set proper model
use App\Service\Router;
use App\Service\Templating;

class MovieController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
        //Wyswietlanie filmow na stronie glownej (dodac "q" w findAll)
        //$movies = Movie::findAll();

        $html = $templating->render('movie/index.html.php', [
            //'movies' => $movies,
            'router' => $router,
        ]);
        return $html;
    }


    public function showAction(int $movieId, Templating $templating, Router $router): ?string
    {
        $movie = Movie::find($movieId);
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
