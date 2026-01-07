<?php
/** @var \App\Model\Movie[] $movies */
/** @var \App\Service\Router $router */
/** @var ?string $query */

$title = 'Movies';
$bodyClass = 'movie-index';

ob_start(); ?>
<header class="app-header">
    <div class="header-container">
        <div class="logo-container">
            <h2 class="logo-text">PLUSFLIX</h2>
        </div>
    </div>
</header>

<main class="app-main">
    <section class="search-section">
        <h1 class="search-title">
            Find where to <span class="search-highlight">watch</span> anything
        </h1>

        <form class="search-form" method="get" action="<?= $router->generatePath('movie-index') ?>">
            <div class="search-container">
                <div class="search-icon">
                    <span class="icon-search">search</span>
                </div>
                <input class="search-input"
                       type="text"
                       name="q"
                       placeholder="Search titles, actors, or genres..."
                       value="<?= htmlspecialchars($query ?? '', ENT_QUOTES) ?>"
                       required>
            </div>
        </form>

        <div class="filters-container">
            <button class="filter-btn" aria-label="Type">
                <span class="filter-text">Type</span>
                <span class="icon-expand">expand_more</span>
            </button>
            <button class="filter-btn" aria-label="Watched">
                <span class="filter-text">Watched</span>
                <span class="icon-check">check</span>
            </button>
            <button class="filter-btn" aria-label="Liked">
                <span class="filter-text">Liked</span>
                <span class="icon-favorite">favorite</span>
            </button>
            <button class="filter-btn" aria-label="Platform">
                <span class="filter-text">Platform</span>
                <span class="icon-expand">expand_more</span>
            </button>
        </div>
    </section>

    <section class="movies-section">
        <div class="section-header">
            <h2 class="section-title">
                <span class="title-accent"></span>
                Trending Now
            </h2>
            <a class="see-all" href="<?= $router->generatePath('movie-index') ?>">
                See all <span class="icon-arrow">arrow_forward</span>
            </a>
        </div>

        <div class="movies-grid">
            <div class="no-results">
                <p>No movies found.</p>
            </div>
        </div>
    </section>
</main>
<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
?>
