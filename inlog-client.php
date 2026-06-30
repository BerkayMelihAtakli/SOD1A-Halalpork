<?php
session_start();
require_once 'product_helpers.php';

render_header('Inloggen klant');
?>
<main class="centering">
    <h2>Inloggen als klant</h2>

    <?php if (isset($_GET['msg'])): ?>
        <p style="color:red;"><strong><?= h($_GET['msg']) ?></strong></p>
    <?php endif; ?>

    <form action="inlog-client-exec.php" method="post" class="tabledisp">
        <label for="email">E-mailadres</label>
        <input type="email" id="email" name="email" required autofocus>

        <label for="wachtwoord">Wachtwoord</label>
        <input type="password" id="wachtwoord" name="wachtwoord" required>

        <p>
            <a href="index.php"><button type="button">Breek af</button></a>
            <input type="submit" value="Log in">
        </p>
    </form>
</main>
<?php render_footer(); ?>
