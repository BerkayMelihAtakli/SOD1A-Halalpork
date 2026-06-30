<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

render_header('Inloggen');
?>
<main class="centering">
    <h2>Inloggen</h2>
    <?php if (isset($_GET['msg'])) { echo '<p><strong>' . h($_GET['msg']) . '</strong></p>'; } ?>

    <form action="inlog-admin.php" method="get" style="margin-bottom: 15px;">
        <button type="submit">Login als beheerder</button>
    </form>

    <form action="inlog-klant.php" method="get">
        <button type="submit">Login als klant</button>
    </form>

    <p>Nog geen account? <a href="cli-crud-add.php">Registreren</a></p>
    <p><a href="index.php">Terug naar home</a></p>
</main>
<?php render_footer(); ?>
