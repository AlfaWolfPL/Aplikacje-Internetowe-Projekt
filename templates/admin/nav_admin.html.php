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
    </div>

</nav>
