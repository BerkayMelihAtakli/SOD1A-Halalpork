<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['upd_client_id'])) {
    header('Location: cli-crud-upd.php');
    exit();
}

$id = (int)$_SESSION['upd_client_id'];

$errors = [];
$client = validate_client_input($errors, false);

// Check email uniqueness (excluding own record)
if (filter_var($client['email'], FILTER_VALIDATE_EMAIL)) {
    $chk = $db->prepare("SELECT COUNT(*) FROM client WHERE email = :email AND id <> :id");
    $chk->execute([':email' => $client['email'], ':id' => $id]);
    if ((int)$chk->fetchColumn() > 0) {
        $errors[] = 'Dit e-mailadres is al in gebruik door een andere klant.';
    }
}

if (!empty($errors)) {
    $_SESSION['client_errors'] = $errors;
    $_SESSION['old_client']    = $_POST;
    header('Location: cli-crud-upd.php');
    exit();
}

// Store validated data in session
$_SESSION['upd_client_data'] = $client;

// Get country name for display
$countryName = '';
if ($client['country'] > 0) {
    $stmt = $db->prepare("SELECT name FROM country WHERE idcountry = :id");
    $stmt->execute([':id' => $client['country']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $countryName = $row['name'] ?? '';
}

render_header('Bevestig wijzigingen');
?>
<main class="centering">
    <h2>Bevestig gewijzigde gegevens</h2>
    <p>Controleer jouw gegevens. Klik op "Bevestigen" om op te slaan.</p>

    <table class="tabledisp2">
        <tr><td>Voornaam</td><td><?php echo h($client['first_name']); ?></td></tr>
        <tr><td>Achternaam</td><td><?php echo h($client['last_name']); ?></td></tr>
        <tr><td>E-mail</td><td><?php echo h($client['email']); ?></td></tr>
        <tr><td>Adres</td><td><?php echo h($client['adress']); ?></td></tr>
        <tr><td>Postcode</td><td><?php echo h($client['zipcode']); ?></td></tr>
        <tr><td>Woonplaats</td><td><?php echo h($client['city']); ?></td></tr>
        <tr><td>Provincie/staat</td><td><?php echo h($client['state']); ?></td></tr>
        <tr><td>Land</td><td><?php echo h($countryName); ?></td></tr>
        <tr><td>Telefoonnummer</td><td><?php echo h($client['telephone']); ?></td></tr>
    </table>

    <p>
        <a href="cli-crud-upd.php"><button type="button">Breek af</button></a>
        <form action="cli-crud-update.php" method="post" style="display:inline">
            <input type="submit" value="Bevestigen">
        </form>
    </p>
</main>
<?php render_footer(); ?>
