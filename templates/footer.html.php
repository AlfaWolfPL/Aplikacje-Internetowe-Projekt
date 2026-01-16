<?php
/** @var \App\Service\Router $router */
?>

    <div class="footer-container">
        <div class="footer-brand">
            <span class="icon-movie">movie</span>
            <span>&copy; <?= date('Y') ?> PLUSFLIX</span>
        </div>
        <nav class="footer-nav">
            <a href="#" class="footer-link">Privacy Policy</a>
            <a href="#" class="footer-link">Terms of Service</a>
            <a href="#" class="footer-link">Help Center</a>
            <a href="<?= $router->generatePath('admin-login') ?>" class="footer-link">Admin Panel</a>
        </nav>
    </div>