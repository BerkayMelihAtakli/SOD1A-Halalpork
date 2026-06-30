<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
require_once 'client_helpers.php';

render_header('Klant wijzigen');

// Alleen beheerder of ingelogde klant mogen dit programma gebruiken
if (!is_admin() && !is_client()) {
    header('Location: inlog-client.php');
    exit();
}

// Beheerder: client_id komt via POST (vanuit cli-crud-get.php) of GET
// Klant: ID altijd uit sessie (geen primary key in formulier nodig)
if (is_admin()) {
    $id = (int)($_POST['client_id'] ?? $_GET['id'] ?? $_SESSION['update_client_id'] ?? 0);
} else {
    $id = (int)($_SESSION['welkNummerIsDit'] ?? 0);
}

if ($id <= 0) {
    $terug = is_admin() ? 'cli-crud-get.php' : 'index.php';
    echo '<main><p>Geen klant gekozen.</p><p><a href="' . $terug . '">Terug</a></p></main>';
    render_footer();
    exit();
}

// Sla ID op in sessie zodat upd01 en update het kunnen verifiëren
$_SESSION['update_client_id'] = $id;

// Haal huidige gegevens op
$stmt = $db->prepare('SELECT * FROM client WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    $terug = is_admin() ? 'cli-crud-get.php' : 'index.php';
    echo '<main><p>Klant niet gevonden.</p><p><a href="' . $terug . '">Terug</a></p></main>';
    render_footer();
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
        if (isset($_SESSION['old_client'])) {
            $client = array_merge($client, $_SESSION['old_client']);
            unset($_SESSION['old_client']);
        }
    }
    $terug = is_admin() ? 'cli-crud-get.php' : 'index.php';
    ?>

    <form action="cli-crud-upd01.php" method="post" class="tabledisp">
        <?php client_form_fields($db, $client, true); ?>
        <p>
            <button type="submit" formaction="<?= $terug ?>">Breek af</button>
            <input type="submit" name="client_update" value="Opslaan">
        </p>
    </form>
</main>
<?php render_footer(); ?>
