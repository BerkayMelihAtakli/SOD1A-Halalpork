<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
require_once 'client_helpers.php';

// Alleen bereikbaar via POST vanuit cli-crud-add.php
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['client_add'])) {
    header('Location: cli-crud-add.php');
    exit();
}

// Valideer alle invoer; e-mail mag nog niet bestaan in de database
$errors = [];
$client = validate_client_input($errors, true, $db);

if (!empty($errors)) {
    // Sla fouten en ingevoerde waarden op in sessie en stuur terug naar het formulier
    $_SESSION['client_errors'] = $errors;
    $_SESSION['old_client']    = $_POST;
    header('Location: cli-crud-add.php');
    exit();
}

// Sla gevalideerde gegevens op in sessie voor cli-crud-adding.php
$_SESSION['pending_client'] = $client;

// Haal landnaam op voor weergave
$stmt = $db->prepare('SELECT name FROM country WHERE idcountry = :id');
$stmt->bindValue(':id', $client['country'], PDO::PARAM_INT);
$stmt->execute();
$countryName = $stmt->fetchColumn() ?: '—';

render_header('Registreren – bevestigen');
?>
<main class="centering">
    <h2>Bevestig jouw gegevens</h2>
    <p>Controleer jouw gegevens en klik op "Bevestigen" om te registreren.</p>
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
    <form action="cli-crud-adding.php" method="post">
        <p>
            <button type="submit" formaction="cli-crud-add.php">Wijzigen</button>
            <input type="submit" name="confirm_register" value="Bevestigen">
        </p>
    </form>
</main>
<?php render_footer(); ?>
