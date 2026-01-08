<?php
/** @var \App\Model\Title[] $titles */
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

        <div class="movie-grid">
            <?php if (empty($titles)): ?>
                <p>Nie znaleziono filmów w bazie.</p>
            <?php else: ?>
                <?php foreach ($titles as $movie): ?>
                    <div class="movie-card">

                        <div class="movie-info">
                            <h3><?= htmlspecialchars($movie->getTitle(), ENT_QUOTES) ?></h3>

                            <div class="meta-row">
                                <span class="match-score"><?= htmlspecialchars(ucfirst($movie->getKind()), ENT_QUOTES) ?></span>

                                <span class="quality-badge">HD</span>
                            </div>

                            <div class="genre-list">
                                <?php
                                $cats = $movie->getCategories();
                                if (!empty($cats)) {
                                    $catNames = array_map(fn($c) => $c->getName(), $cats);
                                    echo htmlspecialchars(implode(' • ', array_slice($catNames, 0, 3)), ENT_QUOTES);
                                }
                                ?>
                            </div>

                            <div class="action-buttons">
                                <span class="material-symbols-outlined">play_arrow</span>
                                <span class="material-symbols-outlined">add</span>
                            </div>

                            <div class="platforms-list" style="font-size: 0.8em; color: #aaa; margin-top: 5px;">
                                <?php
                                $plats = $movie->getPlatforms();
                                $platNames = array_map(fn($p) => $p->getName(), $plats);
                                echo htmlspecialchars(implode(', ', $platNames), ENT_QUOTES);
                                ?>
                            </div>
                            <a href="<?= $router->generatePath('movie-show', ['id' => $movie->getId()]) ?>" class="btn-play">

                            <div class="movie-poster">
                                <img src="https://placehold.co/210x350?text=<?= urlencode($movie->getTitle()) ?>" alt="<?= htmlspecialchars($movie->getTitle(), ENT_QUOTES) ?>">
                            </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
?>
