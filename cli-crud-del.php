<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';
render_header('Klant verwijderen');
require_admin();

$id = (int)($_POST['client_id'] ?? $_GET['id'] ?? 0);
if ($id <= 0) {
    echo '<main><p>Geen klant gekozen.</p><p><a href="cli-crud-get.php">Terug</a></p></main>';
    render_footer();
    exit();
}

// Check for open (non-delivered) orders
$check = $db->prepare(
    "SELECT COUNT(*) FROM purchase WHERE clientid = :id AND delivered = 0"
);
$check->execute([':id' => $id]);
if ((int)$check->fetchColumn() > 0) {
    echo '<main>';
    echo '<h2>Klant kan niet verwijderd worden</h2>';
    echo '<p>Klant mag niet verwijderd worden vanwege openstaande bestellingen.</p>';
    echo '<p><a href="cli-crud-get.php">Terug naar onderhoud klanten</a></p>';
    echo '</main>';
    render_footer();
    exit();
}

$stmt = $db->prepare(
    "SELECT c.*, co.name AS country_name
     FROM client c
     LEFT JOIN country co ON c.country = co.idcountry
     WHERE c.id = :id"
);
$stmt->execute([':id' => $id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    echo '<main><p>Klant niet gevonden.</p><p><a href="cli-crud-get.php">Terug</a></p></main>';
    render_footer();
    exit();
}

$_SESSION['delete_client_id'] = $id;
?>
<main class="centering">
    <h2>Klant verwijderen</h2>
    <p>Weet u zeker dat u deze klant wilt verwijderen?</p>
    <table class="tabledisp2">
        <tr><td>ID</td><td><?php echo h($client['id']); ?></td></tr>
        <tr><td>Voornaam</td><td><?php echo h($client['first_name']); ?></td></tr>
        <tr><td>Achternaam</td><td><?php echo h($client['last_name']); ?></td></tr>
        <tr><td>E-mail</td><td><?php echo h($client['email']); ?></td></tr>
        <tr><td>Adres</td><td><?php echo h($client['adress']); ?></td></tr>
        <tr><td>Postcode</td><td><?php echo h($client['zipcode']); ?></td></tr>
        <tr><td>Woonplaats</td><td><?php echo h($client['city']); ?></td></tr>
        <?php if ($client['state'] !== ''): ?>
        <tr><td>Provincie/staat</td><td><?php echo h($client['state']); ?></td></tr>
        <?php endif; ?>
        <tr><td>Land</td><td><?php echo h($client['country_name'] ?? '—'); ?></td></tr>
        <?php if ($client['telephone'] !== ''): ?>
        <tr><td>Telefoonnummer</td><td><?php echo h($client['telephone']); ?></td></tr>
        <?php endif; ?>
    </table>
    <form action="cli-crud-delete.php" method="post">
        <input type="hidden" name="client_id" value="<?php echo h($client['id']); ?>">
        <p>
            <button type="submit" formaction="cli-crud-get.php">Annuleren</button>
            <input type="submit" name="client_delete" value="Verwijderen">
        </p>
    </form>
</main>
<?php render_footer(); ?>
