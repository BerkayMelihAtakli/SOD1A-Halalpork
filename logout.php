<?php
session_start();
require_once 'product_helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: index.php');
    exit();
}

render_header('Uitloggen');
?>
<main class="centering">
    <h2>Weet u zeker dat u zich wil afmelden?</h2>

    <form action="logout.php" method="post" style="display:inline; margin-right: 10px;">
        <input type="hidden" name="confirm_logout" value="1">
        <button type="submit">Afmelden</button>
    </form>

    <form action="index.php" method="get" style="display:inline;">
        <button type="submit">Breek af</button>
    </form>
</main>
<?php render_footer(); ?>
