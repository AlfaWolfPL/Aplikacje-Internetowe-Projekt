<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie Panel Admina</title>
    <link rel="stylesheet" href="/assets/dist/admin-dashboard.css">
</head>
<body class="login-admin-body">
<div class="form-section-login">
    <h3>Panel Administratora</h3>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="/index.php?action=admin-login">
        <input type="text" class="form-input-login" name="username" placeholder="Login" required><br>
        <input type="password" name="password" class="form-input-login" placeholder="Hasło" required><br>
        <button type="submit" class="btn btn-primary btn-shadow">Zaloguj się</button>
    </form>
</div>
</body>
</html>