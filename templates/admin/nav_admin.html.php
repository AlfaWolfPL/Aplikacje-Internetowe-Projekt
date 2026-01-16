<?php
/** @var \App\Service\Router $router */
?>

<nav class="admin-nav">
    <h1 class="brand-text">
        PLUSFLIX<span class="brand-text-accent">ADMIN</span>
    </h1>

    <div class="nav-links">
        <a href=<?= $router->generatePath('admin-index')?>  class="nav-link">Dashboard</a>
        <a href=<?= $router->generatePath('admin-add')?> class="nav-link">Add items</a>
        <a href=<?= $router->generatePath('platform-index')?> class="nav-link">Streaming Platforms</a>
        <a href=<?= $router->generatePath('category-index')?> class="nav-link">Categories</a>
        <a href="<?= $router->generatePath('admin-logout')?>" class="nav-link">Logout</a>
    </div>

</nav>
