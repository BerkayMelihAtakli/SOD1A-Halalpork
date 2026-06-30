<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
require_once 'client_helpers.php';

// Alleen bereikbaar via POST vanuit cli-crud-upd01.php (knop "Bevestigen")
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['confirm_update'])) {
    header('Location: cli-crud-upd.php');
    exit();
}

// Alleen ingelogde klant
if (!is_client()) {
    header('Location: inlog-client.php');
    exit();
}

// Gevalideerde gegevens moeten in sessie staan (gezet door cli-crud-upd01.php)
if (!isset($_SESSION['pending_update'])) {
    header('Location: cli-crud-upd.php');
    exit();
}

$client = $_SESSION['pending_update'];
$id     = (int)$client['id'];
unset($_SESSION['pending_update'], $_SESSION['update_client_id']);

// Veiligheidscheck: klant mag alleen eigen gegevens wijzigen
if ($id !== (int)($_SESSION['welkNummerIsDit'] ?? 0)) {
    header('Location: index.php');
    exit();
}

// UPDATE met of zonder nieuw wachtwoord
if ($client['pswrd'] !== '') {
    $sql = "UPDATE client
            SET first_name = :first_name, last_name = :last_name, email = :email,
                adress = :adress, zipcode = :zipcode, city = :city, state = :state,
                country = :country, telephone = :telephone, pswrd = :pswrd
            WHERE id = :id";
} else {
    $sql = "UPDATE client
            SET first_name = :first_name, last_name = :last_name, email = :email,
                adress = :adress, zipcode = :zipcode, city = :city, state = :state,
                country = :country, telephone = :telephone
            WHERE id = :id";
}

$stmt = $db->prepare($sql);
$stmt->bindValue(':first_name', $client['first_name']);
$stmt->bindValue(':last_name',  $client['last_name']);
$stmt->bindValue(':email',      $client['email']);
$stmt->bindValue(':adress',     $client['adress']);
$stmt->bindValue(':zipcode',    $client['zipcode']);
$stmt->bindValue(':city',       $client['city']);
$stmt->bindValue(':state',      $client['state']);
$stmt->bindValue(':country',    $client['country'] > 0 ? $client['country'] : null, PDO::PARAM_INT);
$stmt->bindValue(':telephone',  $client['telephone']);
if ($client['pswrd'] !== '') {
    $stmt->bindValue(':pswrd', password_hash($client['pswrd'], PASSWORD_DEFAULT));
}
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

// Bevestiging en terugkeer naar eigen menu
render_header('Wijzigen gelukt');
?>
<main class="centering">
    <h2>Wijzigen gelukt</h2>
    <p>Jouw gegevens zijn succesvol gewijzigd.</p>
    <p><a href="index.php"><button type="button">Terug naar home</button></a></p>
</main>
<?php render_footer(); ?>
