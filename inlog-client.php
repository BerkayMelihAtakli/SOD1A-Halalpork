<?php
session_start();
require_once 'product_helpers.php';

if (!empty($_SESSION['benJeErAl']) && $_SESSION['SoortToegang'] === 'Klant') {
    header('Location: index.php');
    exit();
}

if (empty($_SESSION['csrf_inlog_client'])) {
    $_SESSION['csrf_inlog_client'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen — The Bread Company</title>
    <link rel="stylesheet" href="company.css">
</head>
<body class="hp-page">

<?php include 'nav.html'; ?>

<div class="login-wrap">
    <div class="login-card">
        <h1>Inloggen</h1>
        <p class="login-subtitle">
            Nog geen account? <a href="cli-crud-add.php">Registreer gratis</a>
        </p>

        <?php if (isset($_GET['msg'])): ?>
            <div class="login-error"><?= h($_GET['msg']) ?></div>
        <?php endif; ?>

        <form action="inlog-client-exec.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_inlog_client']) ?>">

            <div class="login-fields">
                <div class="login-field">
                    <label class="login-label" for="email">E-mailadres</label>
                    <input class="login-input" type="email" id="email" name="email"
                           placeholder="jouw@email.com" required autofocus>
                </div>

                <div class="login-field">
                    <div class="login-label-row">
                        <label class="login-label" for="wachtwoord">Wachtwoord</label>
                        <a href="index.php" class="login-forgot">Wachtwoord vergeten?</a>
                    </div>
                    <input class="login-input" type="password" id="wachtwoord" name="wachtwoord"
                           placeholder="••••••••" required>
                </div>
            </div>

            <div class="login-btns">
                <button type="submit" class="login-btn-primary">Inloggen als klant</button>
                <a href="index.php" class="login-btn-cancel">Annuleren</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
