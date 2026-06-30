<?php
session_start();
require_once 'product_helpers.php';

if (!empty($_SESSION['benJeErAl']) && $_SESSION['SoortToegang'] === 'Beheer') {
    header('Location: index.php');
    exit();
}

if (empty($_SESSION['csrf_inlog_admin'])) {
    $_SESSION['csrf_inlog_admin'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen beheerder — The Bread Company</title>
    <link rel="stylesheet" href="company.css">
</head>
<body class="hp-page">

<?php include 'nav.html'; ?>

<div class="login-wrap">
    <div class="login-card">
        <div class="login-badge">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Beheerder
        </div>
        <h1>Inloggen</h1>
        <p class="login-subtitle">
            Toegang alleen voor geautoriseerde beheerders. <a href="inlog-client.php">Klant? Log hier in</a>
        </p>

        <?php if (isset($_GET['msg'])): ?>
            <div class="login-error"><?= h($_GET['msg']) ?></div>
        <?php endif; ?>

        <form action="inlog-admin-exec.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_inlog_admin']) ?>">

            <div class="login-fields">
                <div class="login-field">
                    <label class="login-label" for="email">E-mailadres</label>
                    <input class="login-input" type="email" id="email" name="email"
                           placeholder="beheerder@email.com" required autofocus>
                </div>

                <div class="login-field">
                    <div class="login-label-row">
                        <label class="login-label" for="wachtwoord">Wachtwoord</label>
                    </div>
                    <input class="login-input" type="password" id="wachtwoord" name="wachtwoord"
                           placeholder="••••••••" required>
                </div>
            </div>

            <div class="login-btns">
                <button type="submit" class="login-btn-primary">Inloggen als beheerder</button>
                <a href="index.php" class="login-btn-cancel">Annuleren</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
