<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cli-crud-add.php');
    exit();
}

$errors = [];
$client = validate_client_input($errors, true);

// Check email uniqueness
if (empty($errors) || !in_array('Voer een geldig e-mailadres in.', $errors)) {
    $checkEmail = $db->prepare("SELECT COUNT(*) FROM client WHERE email = :email");
    $checkEmail->execute([':email' => $client['email']]);
    if ((int)$checkEmail->fetchColumn() > 0) {
        $errors[] = 'Dit e-mailadres is al in gebruik.';
    }
}

if (!empty($errors)) {
    $_SESSION['client_errors'] = $errors;
    $_SESSION['old_client']    = $_POST;
    header('Location: cli-crud-add.php');
    exit();
}

// Store validated data + hashed password in session
$_SESSION['reg_client'] = [
    'first_name' => $client['first_name'],
    'last_name'  => $client['last_name'],
    'email'      => $client['email'],
    'adress'     => $client['adress'],
    'zipcode'    => $client['zipcode'],
    'city'       => $client['city'],
    'state'      => $client['state'],
    'country'    => $client['country'],
    'telephone'  => $client['telephone'],
    'pswrd_hash' => password_hash($client['pswrd'], PASSWORD_DEFAULT),
];

// Get country name for display
$countryName = '';
if ($client['country'] > 0) {
    $stmt = $db->prepare("SELECT name FROM country WHERE idcountry = :id");
    $stmt->execute([':id' => $client['country']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $countryName = $row['name'] ?? '';
}

$isAdmin = isset($_SESSION['SoortToegang']) && $_SESSION['SoortToegang'] === 'Beheer';
render_header('Bevestig registratie');
?>
<main class="centering">
    <h2>Bevestig gegevens</h2>
    <p>Controleer jouw gegevens. Klik op "Bevestigen" om te registreren.</p>

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
        <a href="cli-crud-add.php"><button type="button">Breek af</button></a>
        <form action="cli-crud-adding.php" method="post" style="display:inline">
            <input type="submit" value="Bevestigen">
        </form>
    </p>
</main>
<?php render_footer(); ?>
