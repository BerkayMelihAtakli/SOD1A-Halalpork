<?php
// Stap 2: Bevestigingspagina voor het toekennen van beheerrechten
// Alleen toegankelijk via POST van admin-add.php (stap 1)
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

// Toegangscontrole: moet via POST van stap 1 komen
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['client_id'])) {
    header('Location: admin-add.php');
    exit();
}

render_header('Bevestig toekennen beheerrechten');
require_admin();

// Klant-ID uit POST halen en valideren
$id = (int)$_POST['client_id'];
if ($id <= 0) {
    echo '<main><p>Ongeldig klant-ID.</p><p><a href="admin-add.php">Terug</a></p></main>';
    render_footer();
    exit();
}

// Haal alle gegevens van de klant op (inclusief landnaam via JOIN)
// Controleer ook meteen of de klant nog steeds geen beheerder is
$stmt = $db->prepare(
    "SELECT c.*, co.name AS country_name
     FROM client c
     LEFT JOIN country co ON c.country = co.idcountry
     WHERE c.id = :id AND c.isadmin = 'N'"
);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    echo '<main><p>Klant niet gevonden of heeft al beheerrechten.</p>';
    echo '<p><a href="admin-add.php">Terug naar overzicht</a></p></main>';
    render_footer();
    exit();
}

// Sla het klant-ID op in SESSION zodat stap 3 (admin-adding.php) alleen
// via deze pagina bereikt kan worden
$_SESSION['admin_grant_id'] = $id;
?>
<main class="centering">
    <h2>Bevestig toekennen beheerrechten</h2>
    <p>Controleer de gegevens en klik op "Bevestigen" om beheerrechten toe te kennen.</p>

    <!-- Alle klantgegevens worden getoond (wachtwoord nooit tonen) -->
    <table class="tabledisp2">
        <tr><td>ID</td><td><?php echo h($client['id']); ?></td></tr>
        <tr><td>Voornaam</td><td><?php echo h($client['first_name']); ?></td></tr>
        <tr><td>Achternaam</td><td><?php echo h($client['last_name']); ?></td></tr>
        <tr><td>E-mail</td><td><?php echo h($client['email']); ?></td></tr>
        <tr><td>Adres</td><td><?php echo h($client['adress']); ?></td></tr>
        <tr><td>Postcode</td><td><?php echo h($client['zipcode']); ?></td></tr>
        <tr><td>Woonplaats</td><td><?php echo h($client['city']); ?></td></tr>
        <?php if (!empty($client['state'])): ?>
        <tr><td>Provincie/staat</td><td><?php echo h($client['state']); ?></td></tr>
        <?php endif; ?>
        <tr><td>Land</td><td><?php echo h($client['country_name'] ?? '—'); ?></td></tr>
        <?php if (!empty($client['telephone'])): ?>
        <tr><td>Telefoonnummer</td><td><?php echo h($client['telephone']); ?></td></tr>
        <?php endif; ?>
    </table>

    <!-- Formulier met twee knoppen: afbreken of bevestigen -->
    <form action="admin-adding.php" method="post">
        <input type="hidden" name="client_id" value="<?php echo h($client['id']); ?>">
        <p>
            <!-- Breek af: keert terug naar homepage zonder DB-wijziging -->
            <button type="submit" formaction="index.php">Breek af</button>
            <input type="submit" name="confirm_admin" value="Bevestigen">
        </p>
    </form>
</main>
<?php render_footer(); ?>
