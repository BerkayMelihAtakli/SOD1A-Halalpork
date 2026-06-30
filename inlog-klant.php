<?php
session_start();
require_once 'product_helpers.php';

if (!empty($_SESSION['benJeErAl']) && $_SESSION['SoortToegang'] === 'Klant') {
    header('Location: index.php');
    exit();
}

if (empty($_SESSION['csrf_inlog_klant'])) {
    $_SESSION['csrf_inlog_klant'] = bin2hex(random_bytes(32));
}

render_header('Inloggen klant');
?>
<main>
    <h2>Inloggen als klant</h2>

    <?php if (isset($_GET['msg'])) { ?>
        <p style="color:red;"><strong><?= h($_GET['msg']) ?></strong></p>
    <?php } ?>

    <form action="inlog-klant-exec.php" method="post" class="tabledisp">
        <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_inlog_klant']) ?>">

        <label for="email">E-mailadres</label>
        <input type="email" id="email" name="email" required autofocus>

        <label for="wachtwoord">Wachtwoord</label>
        <input type="password" id="wachtwoord" name="wachtwoord" required>

        <p>
            <a href="index.php"><button type="button">Annuleren</button></a>
            <button type="submit">Inloggen</button>
        </p>
    </form>

    <p>Nog geen account? <a href="cli-crud-add.php">Registreren</a></p>
</main>
<?php render_footer(); ?>
