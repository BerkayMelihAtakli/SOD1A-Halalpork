<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
require_once 'client_helpers.php';

// Zowel beheerder als bezoeker kunnen dit formulier invullen
$isAdmin = isset($_SESSION['SoortToegang']) && $_SESSION['SoortToegang'] === 'Beheer';
$title   = $isAdmin ? 'Klant toevoegen' : 'Registreren';
render_header($title);
?>
<main class="centering">
    <h2><?= h($title) ?></h2>

    <?php
    // Toon validatiefouten uit vorige poging
    if (isset($_SESSION['client_errors'])) {
        echo '<ul>';
        foreach ($_SESSION['client_errors'] as $fout) {
            echo '<li>' . h($fout) . '</li>';
        }
        echo '</ul>';
        unset($_SESSION['client_errors']);
    }
    // Herstel eerder ingevulde waarden
    $old = $_SESSION['old_client'] ?? [];
    unset($_SESSION['old_client']);
    ?>

    <form action="cli-crud-add01.php" method="post" class="tabledisp">
        <?php client_form_fields($db, $old, true); ?>
        <p>
            <?php if ($isAdmin): ?>
                <button type="submit" formaction="cli-crud-get.php">Breek af</button>
            <?php else: ?>
                <a href="inlog-client.php">Al een account? Inloggen</a>
            <?php endif; ?>
            <input type="submit" name="client_add" value="<?= $isAdmin ? 'Sla op' : 'Registreren' ?>">
        </p>
    </form>
</main>
<?php render_footer(); ?>
