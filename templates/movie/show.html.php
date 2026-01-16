<?php
/** @var \App\Model\Title $movie */
/** @var \App\Service\Router $router */

$title = $movie->getTitle() . ' - PLUSFLIX';
$bodyClass = 'movie-show';

ob_start(); ?>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0" />
    <link rel="stylesheet" href="/assets/dist/test.css"/>
    <header class="pf-header">

        <div class="pf-container header-inner">
            <div class="header-left">
                <div class="logo">
                    <span class="logo-icon"></span>
                    <span class="logo-text">PLUSFLIX</span>
                </div>
                <nav class="main-nav">
                    <a href="<?= $router->generatePath('movie-index') ?>" class="nav-item">Movies</a>
<!--                    <a href="#" class="nav-item">Series</a>-->
<!--                    <a href="#" class="nav-item">New & Popular</a>-->
                </nav>
            </div>
            <div class="header-right">
                <div class="search-bar">
                    <span class="material-symbols-outlined search-icon">search</span>
                    <input type="text" placeholder="Search titles, people..." class="search-input">
                </div>
            </div>
        </div>
    </header>

    <main class="pf-main">
        <div class="background-backdrop" style="background-image: url('https://placehold.co/1920x1080?text=<?= urlencode($movie->getTitle()) ?>');"></div>
        <div class="background-overlay"></div>

        <div class="pf-container content-grid">

            <div class="poster-column">
                <div class="poster-card">
                    <img src="https://placehold.co/400x600?text=<?= urlencode($movie->getTitle()) ?>"
                         alt="<?= htmlspecialchars($movie->getTitle()) ?>"
                         class="main-poster">

                </div>
            </div>

            <div class="details-column">
                <h1 class="movie-title"><?= htmlspecialchars($movie->getTitle(), ENT_QUOTES) ?></h1>

                <div class="meta-row">
                    <?php if ($movie->getYear()): ?>
                        <span class="meta-year"><?= htmlspecialchars($movie->getYear()) ?></span>
                        <span class="meta-dot">•</span>
                    <?php endif; ?>

<!--                    <span class="meta-badge">TV-14</span> <span class="meta-dot">•</span>-->
<!---->
<!--                    <span class="meta-duration">4 Seasons</span> <span class="meta-dot">•</span>-->

                    <?php
                    $cats = $movie->getCategories();
                    if (!empty($cats)):
                        $catNames = array_map(fn($c) => $c->getName(), $cats);
                        ?>
                        <span class="meta-genre"><?= htmlspecialchars(implode(', ', array_slice($catNames, 0, 2))) ?></span>
                    <?php endif; ?>
                </div>

                <div class="action-buttons">
                    <button class="btn btn-primary">
                        <span class="material-symbols-outlined">play_arrow</span>
                        Watch Trailer
                    </button>
                    <button class="btn btn-secondary">
                        <span class="material-symbols-outlined">add</span>
                        Add to Watched
                    </button>
                    <button class="btn btn-secondary">
                        <span class="material-symbols-outlined">favorite</span>
                        Add to Liked
                    </button>
                </div>

                <div class="section-block">
                    <h3 class="section-label">Synopsis</h3>
                    <p class="synopsis-text">
                        <?= $movie->getDescription() ? nl2br(htmlspecialchars($movie->getDescription())) : 'When a young boy vanishes, a small town uncovers a mystery involving secret experiments, terrifying supernatural forces, and one strange little girl.' ?>
                    </p>
                </div>

<!--                <div class="stats-row">-->
<!--                    <div class="stat-item">-->
<!--                        <span class="material-symbols-outlined thumb-icon">thumb_up</span>-->
<!--                        <span>24k</span>-->
<!--                    </div>-->
<!--                    <div class="stat-divider">|</div>-->
<!--                    <div class="stat-item">-->
<!--                        <span class="material-symbols-outlined thumb-icon icon-flip">thumb_down</span>-->
<!--                        <span>1.2k</span>-->
<!--                    </div>-->
<!--                    <div class="stat-item star-rating">-->
<!--                        <span class="material-symbols-outlined star-icon">star</span>-->
<!--                        <span class="score-val">8.7</span>-->
<!--                        <span class="score-max">/ 10 IMDb</span>-->
<!--                    </div>-->
<!--                </div>-->

                <div class="section-block availability-section">
                    <h3 class="section-label icon-label">
                        <span class="material-symbols-outlined yellow-icon">smart_display</span>
                        Streaming Availability
                    </h3>

                    <div class="platforms-list">
                        <?php
                        $platforms = $movie->getPlatforms();
                        if (!empty($platforms)):
                            foreach ($platforms as $platform): ?>
                                <div class="platform-card">
                                    <div class="platform-logo-box">
                                        <span class="platform-letter"><?= strtoupper(substr($platform->getName(), 0, 1)) ?></span>
                                    </div>
                                    <div class="platform-info">
                                        <span class="platform-name"><?= htmlspecialchars($platform->getName()) ?></span>
                                        <span class="platform-sub">Subscription • 4K</span>
                                    </div>
                                    <span class="material-symbols-outlined open-icon">open_in_new</span>
                                </div>
                            <?php endforeach;
                        else: ?>
                            <div class="platform-card">
                                <div class="platform-logo-box netflix-bg">N</div>
                                <div class="platform-info">
                                    <span class="platform-name">Netflix</span>
                                    <span class="platform-sub">Subscription • 4K</span>
                                </div>
                                <span class="material-symbols-outlined open-icon">open_in_new</span>
                            </div>
                            <div class="platform-card">
                                <div class="platform-logo-box prime-bg">P</div>
                                <div class="platform-info">
                                    <span class="platform-name">Prime Video</span>
                                    <span class="platform-sub">Buy / Rent</span>
                                </div>
                                <span class="material-symbols-outlined open-icon">open_in_new</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="section-block cast-section">
<!--                    <h3 class="section-label">Top Cast</h3>-->
<!--                    <div class="cast-list">-->
<!--                        <div class="cast-member">-->
<!--                            <img src="https://placehold.co/50x50" alt="Actor" class="cast-avatar">-->
<!--                            <div class="cast-info">-->
<!--                                <span class="cast-name">Millie B. Brown</span>-->
<!--                               <span class="cast-role">Eleven</span>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="cast-member">-->
<!--                            <img src="https://placehold.co/50x50" alt="Actor" class="cast-avatar">-->
<!--                            <div class="cast-info">-->
<!--                                <span class="cast-name">Finn Wolfhard</span>-->
<!--                                <span class="cast-role">Mike Wheeler</span>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="cast-member">-->
<!--                            <img src="https://placehold.co/50x50" alt="Actor" class="cast-avatar">-->
<!--                            <div class="cast-info">-->
<!--                                <span class="cast-name">Winona Ryder</span>-->
<!--                                <span class="cast-role">Joyce Byers</span>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                </div>

            </div>
        </div>
    </main>



<?php $main = ob_get_clean();
include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php'; ?>