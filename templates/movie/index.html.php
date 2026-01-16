<?php
/** @var \App\Model\Title[] $titles */
/** @var \App\Service\Router $router */
/** @var array $queryParams */
/** @var \App\Model\Category[] $categories */
/** @var \App\Model\Platform[] $platforms */

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

        <form action="<?= $router->generatePath('movie-index') ?>" method="GET" class="search-form">
            <div class="search-container">
                <span class="search-icon material-symbols-outlined">search</span>
                <input type="text" name="q" class="search-input"
                       placeholder="Search titles, actors, or genres..."
                       value="<?= htmlspecialchars($queryParams['q'] ?? '') ?>">
            </div>

            <div class="filters-container">

                <div style="position: relative;">
                    <span class="material-symbols-outlined filter-btn-select">category</span>
                    <select name="category" class="search-input">
                        <option value="">Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat->getId() ?>" <?= ($queryParams['category'] == $cat->getId()) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat->getName()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="position: relative;">
                    <span class="material-symbols-outlined filter-btn-select">tv</span>
                    <select name="platform" class="search-input">
                        <option value="">Platforms</option>
                        <?php foreach ($platforms as $plat): ?>
                            <option value="<?= $plat->getId() ?>" <?= ($queryParams['platform'] == $plat->getId()) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($plat->getName()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="position: relative;">
                    <span class="material-symbols-outlined" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-gray); pointer-events: none; font-size: 1.2rem;">movie_filter</span>
                    <select name="kind" class="search-input" style="padding-left: 2.8rem; cursor: pointer; appearance: none;">
                        <option value="">All kinds</option>
                        <option value="movie" <?= ($queryParams['kind'] === 'movie') ? 'selected' : '' ?>>Movies</option>
                        <option value="series" <?= ($queryParams['kind'] === 'series') ? 'selected' : '' ?>>Series</option>
                    </select>
                </div>

                <button type="submit" class="filter-btn" style="background-color: var(--primary-color); color: black; border: none; font-weight: 700;">
                    Search
                </button>

                <?php if (!empty($queryParams['q']) || !empty($queryParams['category']) || !empty($queryParams['platform'])): ?>
                    <a href="<?= $router->generatePath('movie-index') ?>" class="filter-btn" style="text-decoration: none; color: var(--text-gray);">
                        Clear
                    </a>
                <?php endif; ?>
            </div>
        </form>
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
                <div class="no-results">
                    <p>No movies found.</p>
                </div>
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
    <script src="assets/js/autocomplete.js"></script>
</main>
<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
?>
