<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

// Alleen beheerders mogen klanten verwijderen
render_header('Klant verwijderen');
require_admin();

$id = (int)($_POST['client_id'] ?? $_GET['id'] ?? 0);
if ($id <= 0) {
    echo '<main><p>Geen klant gekozen.</p><p><a href="cli-crud-get.php">Terug</a></p></main>';
    render_footer();
    exit();
}

// Controleer op openstaande bestellingen (delivered = 0)
$check = $db->prepare('SELECT COUNT(*) FROM purchase WHERE clientid = :id AND delivered = 0');
$check->bindValue(':id', $id, PDO::PARAM_INT);
$check->execute();
if ((int)$check->fetchColumn() > 0) {
    echo '<main><h2>Klant kan niet verwijderd worden</h2>';
    echo '<p>Klant mag niet verwijderd worden vanwege openstaande bestellingen.</p>';
    echo '<p><a href="cli-crud-get.php">Terug naar onderhoud klanten</a></p></main>';
    render_footer();
    exit();
}

// Haal klantgegevens op
$stmt = $db->prepare('SELECT c.*, co.name AS country_name
                      FROM client c
                      LEFT JOIN country co ON c.country = co.idcountry
                      WHERE c.id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    echo '<main><p>Klant niet gevonden.</p><p><a href="cli-crud-get.php">Terug</a></p></main>';
    render_footer();
    exit();
}

// Controleer of de klant een beheerder is
if ($client['isadmin'] === 'J') {
    echo '<main><h2>Klant kan niet verwijderd worden</h2>';
    echo '<p>Klant is beheerder en mag niet verwijderd worden.</p>';
    echo '<p><a href="cli-crud-get.php">Terug naar onderhoud klanten</a></p></main>';
    render_footer();
    exit();
}

// Sla ID op in sessie voor verificatie in cli-crud-delete.php
$_SESSION['delete_client_id'] = $id;
?>
<main class="centering">
    <h2>Klant verwijderen</h2>
    <p>Weet je zeker dat je deze klant wilt verwijderen?</p>
    <table class="tabledisp2">
        <tr><td>ID</td>            <td><?= h($client['id'])           ?></td></tr>
        <tr><td>Voornaam</td>      <td><?= h($client['first_name'])   ?></td></tr>
        <tr><td>Achternaam</td>    <td><?= h($client['last_name'])    ?></td></tr>
        <tr><td>E-mail</td>        <td><?= h($client['email'])        ?></td></tr>
        <tr><td>Adres</td>         <td><?= h($client['adress'])       ?></td></tr>
        <tr><td>Postcode</td>      <td><?= h($client['zipcode'])      ?></td></tr>
        <tr><td>Woonplaats</td>    <td><?= h($client['city'])         ?></td></tr>
        <tr><td>Land</td>          <td><?= h($client['country_name']) ?></td></tr>
        <tr><td>Telefoonnummer</td><td><?= h($client['telephone'])    ?></td></tr>
    </table>
    <form action="cli-crud-delete.php" method="post">
        <input type="hidden" name="client_id" value="<?= h($client['id']) ?>">
        <button type="submit" formaction="cli-crud-get.php">Breek af</button>
        <input type="submit" name="client_delete" value="Verwijderen">
    </form>
</main>
<?php render_footer(); ?>
