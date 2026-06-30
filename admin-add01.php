<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

// Stap 2: alleen bereikbaar via POST vanuit admin-add.php
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['client_id'])) {
    header('Location: admin-add.php');
    exit();
}

require_admin();

$id = (int)$_POST['client_id'];
if ($id <= 0) {
    header('Location: admin-add.php');
    exit();
}

// Haal alle gegevens van de geselecteerde klant op
$stmt = $db->prepare('SELECT c.*, co.name AS country_name
                      FROM client c
                      LEFT JOIN country co ON c.country = co.idcountry
                      WHERE c.id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$client = $stmt->fetch(PDO::FETCH_ASSOC);

// Klant moet bestaan en nog geen beheerder zijn
if (!$client || $client['isadmin'] === 'J') {
    header('Location: admin-add.php');
    exit();
}

// Sla geselecteerd ID op in sessie voor verificatie in admin-adding.php
$_SESSION['admin_add_id'] = $id;

render_header('Bevestig toekennen beheerrechten');
?>
<main class="centering">
    <h2>Bevestig toekennen beheerrechten</h2>
    <table class="tabledisp2">
        <tr><td>ID</td>            <td><?= h($client['id'])           ?></td></tr>
        <tr><td>Voornaam</td>      <td><?= h($client['first_name'])   ?></td></tr>
        <tr><td>Achternaam</td>    <td><?= h($client['last_name'])    ?></td></tr>
        <tr><td>E-mail</td>        <td><?= h($client['email'])        ?></td></tr>
        <tr><td>Adres</td>         <td><?= h($client['adress'])       ?></td></tr>
        <tr><td>Postcode</td>      <td><?= h($client['zipcode'])      ?></td></tr>
        <tr><td>Woonplaats</td>    <td><?= h($client['city'])         ?></td></tr>
        <tr><td>Provincie/staat</td><td><?= h($client['state'])       ?></td></tr>
        <tr><td>Land</td>          <td><?= h($client['country_name']) ?></td></tr>
        <tr><td>Telefoonnummer</td><td><?= h($client['telephone'])    ?></td></tr>
    </table>
    <p>
        <a href="index.php"><button type="button">Breek af</button></a>
        <form action="admin-adding.php" method="post" style="display:inline;">
            <input type="submit" name="confirm_admin" value="Bevestigen">
        </form>
    </p>
</main>
<?php render_footer(); ?>
