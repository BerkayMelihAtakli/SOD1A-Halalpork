<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['update_client_id'])) {
    header('Location: cli-crud-upd.php');
    exit();
}

if (!is_admin() && !is_client()) {
    header('Location: login.php');
    exit();
}

$id = (int)$_SESSION['update_client_id'];
$errors = [];
$client = validate_client_input($errors, false, $db, $id);

if (!empty($errors)) {
    $_SESSION['client_errors'] = $errors;
    $_SESSION['old_client'] = $_POST;
    header('Location: cli-crud-upd.php');
    exit();
}

$_SESSION['pending_update'] = $client;
$_SESSION['pending_update']['id'] = $id;

$stmt = $db->prepare('SELECT name FROM country WHERE idcountry = :id');
$stmt->execute([':id' => $client['country']]);
$countryName = $stmt->fetchColumn() ?: '—';

render_header('Wijziging bevestigen');
?>
<main class="centering">
    <h2>Wijziging bevestigen</h2>
    <p>Controleer de gewijzigde gegevens en klik op "Bevestigen" om op te slaan.</p>
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
    <form action="cli-crud-update.php" method="post">
        <p>
            <button type="submit" formaction="cli-crud-upd.php">Wijzigen</button>
            <input type="submit" name="confirm_update" value="Bevestigen">
        </p>
    </form>
</main>
<?php render_footer(); ?>
