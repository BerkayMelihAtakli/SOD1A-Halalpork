<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cli-crud-add.php');
    exit();
}

$errors = [];
$client = validate_client_input($errors, true, $db, 0);

if (!empty($errors)) {
    $_SESSION['client_errors'] = $errors;
    $_SESSION['old_client'] = $_POST;
    header('Location: cli-crud-add.php');
    exit();
}

$_SESSION['pending_client'] = $client;
$_SESSION['pending_client']['pswrd'] = $_POST['pswrd'];

$stmt = $db->prepare('SELECT name FROM country WHERE idcountry = :id');
$stmt->execute([':id' => $client['country']]);
$countryName = $stmt->fetchColumn() ?: '—';

render_header('Registratie bevestigen');
?>
<main class="centering">
    <h2>Registratie bevestigen</h2>
    <p>Controleer uw gegevens en klik op "Bevestigen" om te registreren.</p>
    <table class="tabledisp2">
        <tr><td>Voornaam</td><td><?php echo h($client['first_name']); ?></td></tr>
        <tr><td>Achternaam</td><td><?php echo h($client['last_name']); ?></td></tr>
        <tr><td>E-mail</td><td><?php echo h($client['email']); ?></td></tr>
        <tr><td>Adres</td><td><?php echo h($client['adress']); ?></td></tr>
        <tr><td>Postcode</td><td><?php echo h($client['zipcode']); ?></td></tr>
        <tr><td>Woonplaats</td><td><?php echo h($client['city']); ?></td></tr>
        <?php if ($client['state'] !== ''): ?>
        <tr><td>Provincie/staat</td><td><?php echo h($client['state']); ?></td></tr>
        <?php endif; ?>
        <tr><td>Land</td><td><?php echo h($countryName); ?></td></tr>
        <?php if ($client['telephone'] !== ''): ?>
        <tr><td>Telefoonnummer</td><td><?php echo h($client['telephone']); ?></td></tr>
        <?php endif; ?>
    </table>
    <form action="cli-crud-adding.php" method="post">
        <p>
            <button type="submit" formaction="cli-crud-add.php">Wijzigen</button>
            <input type="submit" name="confirm_register" value="Bevestigen">
        </p>
    </form>
</main>
<?php render_footer(); ?>
