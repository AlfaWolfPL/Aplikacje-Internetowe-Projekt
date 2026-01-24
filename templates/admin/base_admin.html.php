<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'PLUSFLIX Admin' ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap">

    <link rel="stylesheet" href="/assets/dist/admin-dashboard.css">
</head>
<body class="admin-body">

<?php include __DIR__ . '/nav_admin.html.php'; ?>

<main class="admin-main">
    <?= $main ?? '' ?>
</main>

<footer class="admin-footer">
    <div class="admin-footer-inner">
        <p class="footer-copy">© <?= date('Y') ?> PLUSFLIX</p>
        </div>
    </div>
</footer>

</body>
</html>
