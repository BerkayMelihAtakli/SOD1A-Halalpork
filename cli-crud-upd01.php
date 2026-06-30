<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
require_once 'client_helpers.php';

// Alleen bereikbaar via POST vanuit cli-crud-upd.php
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['client_update'])) {
    header('Location: cli-crud-upd.php');
    exit();
}

// Alleen ingelogde klant
if (!is_client()) {
    header('Location: inlog-client.php');
    exit();
}

// ID moet in sessie staan (gezet door cli-crud-upd.php)
if (!isset($_SESSION['update_client_id'])) {
    header('Location: cli-crud-upd.php');
    exit();
}

$id = (int)$_SESSION['update_client_id'];

// Valideer alle invoer; e-mail mag niet al bestaan bij een andere klant
$errors = [];
$client = validate_client_input($errors, false, $db, $id);

if (!empty($errors)) {
    $_SESSION['client_errors'] = $errors;
    $_SESSION['old_client']    = $_POST;
    header('Location: cli-crud-upd.php');
    exit();
}

// Sla gevalideerde gegevens op in sessie voor cli-crud-update.php
$_SESSION['pending_update']       = $client;
$_SESSION['pending_update']['id'] = $id;

// Haal landnaam op voor weergave
$stmt = $db->prepare('SELECT name FROM country WHERE idcountry = :id');
$stmt->bindValue(':id', $client['country'], PDO::PARAM_INT);
$stmt->execute();
$countryName = $stmt->fetchColumn() ?: '—';

render_header('Wijziging bevestigen');
?>
<main class="centering">
    <h2>Wijziging bevestigen</h2>
    <p>Controleer de gewijzigde gegevens en klik op "Bevestigen" om op te slaan.</p>
    <table class="tabledisp2">
        <tr><td>Voornaam</td>        <td><?= h($client['first_name']) ?></td></tr>
        <tr><td>Achternaam</td>      <td><?= h($client['last_name'])  ?></td></tr>
        <tr><td>E-mail</td>          <td><?= h($client['email'])      ?></td></tr>
        <tr><td>Adres</td>           <td><?= h($client['adress'])     ?></td></tr>
        <tr><td>Postcode</td>        <td><?= h($client['zipcode'])    ?></td></tr>
        <tr><td>Woonplaats</td>      <td><?= h($client['city'])       ?></td></tr>
        <?php if ($client['state'] !== ''): ?>
        <tr><td>Provincie/staat</td> <td><?= h($client['state'])      ?></td></tr>
        <?php endif; ?>
        <tr><td>Land</td>            <td><?= h($countryName)          ?></td></tr>
        <?php if ($client['telephone'] !== ''): ?>
        <tr><td>Telefoonnummer</td>  <td><?= h($client['telephone'])  ?></td></tr>
        <?php endif; ?>
    </table>
    <p><em>Wachtwoord wordt niet getoond.</em></p>
    <form action="cli-crud-update.php" method="post">
        <p>
            <button type="submit" formaction="cli-crud-upd.php">Wijzigen</button>
            <input type="submit" name="confirm_update" value="Bevestigen">
        </p>
    </form>
</main>
<?php render_footer(); ?>
