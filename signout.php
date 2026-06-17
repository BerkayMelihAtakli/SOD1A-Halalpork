<?php
session_start();
require_once 'product_helpers.php';


if (!isset($_SESSION['benJeErAl']) || $_SESSION['benJeErAl'] !== true) {
    header('Location: index.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['afmelden'])) {
    unset($_SESSION['benJeErAl']);
    unset($_SESSION['SoortToegang']);
    session_destroy();
    header('Location: index.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['breekaf'])) {
    header('Location: index.php');
    exit();
}

render_header('Afmelden');
?>
<main>
    <h2>Afmelden</h2>
    <p>Weet u zeker dat u zich wil afmelden?</p>

    <form method="post" style="display:inline-block; margin-right:10px;">
        <button type="submit" name="afmelden">Afmelden</button>
    </form>

    <form method="post" style="display:inline-block;">
        <button type="submit" name="breekaf">Breek af</button>
    </form>
</main>
<?php render_footer(); ?>
