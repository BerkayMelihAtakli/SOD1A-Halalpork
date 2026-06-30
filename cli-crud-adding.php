<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

// Alleen bereikbaar via POST vanuit cli-crud-add01.php (knop "Bevestigen")
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['confirm_register'])) {
    header('Location: cli-crud-add.php');
    exit();
}

// Gevalideerde gegevens moeten in sessie staan (gezet door cli-crud-add01.php)
if (!isset($_SESSION['pending_client'])) {
    header('Location: cli-crud-add.php');
    exit();
}

$client = $_SESSION['pending_client'];
unset($_SESSION['pending_client']);

// Voeg nieuwe klant in; isadmin altijd 'N' (wordt door programma ingevuld)
$sql = "INSERT INTO client
        (first_name, last_name, email, adress, zipcode, city, state, country, telephone, isadmin, pswrd)
        VALUES
        (:first_name, :last_name, :email, :adress, :zipcode, :city, :state, :country, :telephone, 'N', :pswrd)";

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
$stmt->bindValue(':pswrd',      password_hash($client['pswrd'], PASSWORD_DEFAULT));
$stmt->execute();

render_header('Registreren gelukt');
?>
<main class="centering">
    <h2>Registreren gelukt</h2>
    <p>Jouw account is succesvol aangemaakt. Je kunt nu inloggen.</p>
    <p><a href="inlog-client.php"><button type="button">Inloggen</button></a></p>
</main>
<?php render_footer(); ?>
