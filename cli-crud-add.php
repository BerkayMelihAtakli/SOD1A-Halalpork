<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';
render_header('Klant toevoegen');
require_admin();
?>
<main class="centering">
    <h2>Klant toevoegen</h2>
    <?php
    if (isset($_SESSION['client_errors'])) {
        echo '<ul>';
        foreach ($_SESSION['client_errors'] as $error) {
            echo '<li>' . h($error) . '</li>';
        }
        echo '</ul>';
        unset($_SESSION['client_errors']);
    }
    $old = $_SESSION['old_client'] ?? [];
    unset($_SESSION['old_client']);
    ?>
    <form action="cli-crud-adding.php" method="post" class="tabledisp">
        <?php client_form_fields($db, $old, true); ?>
        <p>
            <button type="submit" formaction="cli-crud-get.php">Breek af</button>
            <input type="submit" name="client_add" value="Sla op">
        </p>
    </form>
</main>
<?php render_footer(); ?>
