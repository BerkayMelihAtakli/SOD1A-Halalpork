<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';

$isAdmin = isset($_SESSION['SoortToegang']) && $_SESSION['SoortToegang'] === 'Beheer';
$title   = $isAdmin ? 'Klant toevoegen' : 'Registreren';
render_header($title);
?>
<main class="centering">
    <h2><?php echo h($title); ?></h2>
    <?php
    if (isset($_SESSION['client_errors'])) {
        echo '<ul>';
        foreach ($_SESSION['client_errors'] as $error) {
            echo '<li style="color:red">' . h($error) . '</li>';
        }
        echo '</ul>';
        unset($_SESSION['client_errors']);
    }
    $old = $_SESSION['old_client'] ?? [];
    unset($_SESSION['old_client']);
    ?>
    <form action="cli-crud-add01.php" method="post" class="tabledisp">
        <?php client_form_fields($db, $old, true); ?>
        <p>
            <?php if ($isAdmin): ?>
                <button type="submit" formaction="cli-crud-get.php">Breek af</button>
            <?php else: ?>
                <a href="inlog-client.php">Al een account? Inloggen</a>&nbsp;
            <?php endif; ?>
            <input type="submit" value="Verder">
        </p>
    </form>
</main>
<?php render_footer(); ?>
