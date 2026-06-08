<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

render_header('Inloggen');
?>
<main class="centering">
    <h2>Inloggen</h2>

    <?php
    if (isset($_GET['msg'])) {
        echo '<p><strong>' . h($_GET['msg']) . '</strong></p>';
    }
    ?>

    <h3>Normaal inloggen</h3>
    <form action="login-check.php" method="post" class="tabledisp">
        <label>E-mailadres</label>
        <input type="email" name="email" required>

        <label>Wachtwoord</label>
        <input type="password" name="password" required>

        <p><input type="submit" value="Inloggen"></p>
    </form>

    <p>Testwachtwoord voor de demo accounts: <strong>halalpork123</strong></p>

    <h3>Demo login</h3>
    <form action="admin-login.php" method="post" style="margin-bottom: 15px;">
        <button type="submit">Login als beheerder</button>
    </form>

    <form action="client-login.php" method="post">
        <button type="submit">Login als klant</button>
    </form>

    <p><a href="change-password.php">Wachtwoord wijzigen</a></p>
    <p><a href="index.php">Terug naar home</a></p>
</main>
<?php render_footer(); ?>
