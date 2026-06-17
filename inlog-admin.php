<?php
session_start();
require_once 'product_helpers.php';

if (empty($_SESSION['csrf_inlog_admin'])) {
    $_SESSION['csrf_inlog_admin'] = bin2hex(random_bytes(32));
}

render_header('Inloggen beheerder');
?>
<main>
    <h2>Inloggen als beheerder</h2>

    <?php if (isset($_GET['msg'])) { ?>
        <p style="color:red;"><strong><?= h($_GET['msg']) ?></strong></p>
    <?php } ?>

    <form action="inlog-admin-exec.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_inlog_admin']) ?>">

        <label for="email">E-mailadres</label><br>
        <input type="email" id="email" name="email" required autofocus><br><br>

        <label for="wachtwoord">Wachtwoord</label><br>
        <input type="password" id="wachtwoord" name="wachtwoord" required><br><br>

        <a href="index.php"><button type="button">Breek af</button></a>
        <button type="submit">Log in</button>
    </form>
</main>
<?php render_footer(); ?>
