<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
require_once 'client_helpers.php';

render_header('Klant wijzigen');

// Alleen ingelogde klant mag eigen gegevens wijzigen
if (!is_client()) {
    header('Location: inlog-client.php');
    exit();
}

// ID van de ingelogde klant uit de sessie (geen primary key in formulier nodig)
$id = (int)($_SESSION['welkNummerIsDit'] ?? 0);
if ($id <= 0) {
    header('Location: index.php');
    exit();
}

// Sla ID op in sessie zodat cli-crud-upd01.php en cli-crud-update.php het kunnen verifiëren
$_SESSION['update_client_id'] = $id;

// Haal huidige gegevens op
$stmt = $db->prepare('SELECT * FROM client WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    header('Location: index.php');
    exit();
}
?>
<main class="centering">
    <h2>Klant wijzigen</h2>

    <?php
    // Toon validatiefouten uit vorige poging
    if (isset($_SESSION['client_errors'])) {
        echo '<ul>';
        foreach ($_SESSION['client_errors'] as $fout) {
            echo '<li>' . h($fout) . '</li>';
        }
        echo '</ul>';
        unset($_SESSION['client_errors']);
        // Herstel eerder ingevulde waarden
        if (isset($_SESSION['old_client'])) {
            $client = array_merge($client, $_SESSION['old_client']);
            unset($_SESSION['old_client']);
        }
    }
    ?>

    <form action="cli-crud-upd01.php" method="post" class="tabledisp">
        <?php
        // Toon invoervelden met huidige waarden (zonder wachtwoordveld verplicht)
        client_form_fields($db, $client, true);
        ?>
        <p>
            <button type="submit" formaction="index.php">Breek af</button>
            <input type="submit" name="client_update" value="Opslaan">
        </p>
    </form>
</main>
<?php render_footer(); ?>
