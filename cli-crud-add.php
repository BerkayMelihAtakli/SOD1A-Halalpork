<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';
render_header('Klant registreren');
?>
<main class="centering">
    <h2>Registreren</h2>
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
    <form action="cli-crud-add01.php" method="post" class="tabledisp">
        <?php client_form_fields($db, $old, true); ?>
        <p>
            <button type="submit" formaction="index.php">Annuleren</button>
            <input type="submit" name="client_register" value="Registreren">
        </p>
    </form>
</main>
<?php render_footer(); ?>
