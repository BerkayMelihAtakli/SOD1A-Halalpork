<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';

if (!isset($_SESSION['pending_client'])) {
    header('Location: cli-crud-add.php');
    exit();
}

$client = $_SESSION['pending_client'];
unset($_SESSION['pending_client']);

$sql = "INSERT INTO client (first_name, last_name, email, adress, zipcode, city, state, country, telephone, isadmin, pswrd)
        VALUES (:first_name, :last_name, :email, :adress, :zipcode, :city, :state, :country, :telephone, 'N', :pswrd)";
$stmt = $db->prepare($sql);
$stmt->execute([
    ':first_name' => $client['first_name'],
    ':last_name'  => $client['last_name'],
    ':email'      => $client['email'],
    ':adress'     => $client['adress'],
    ':zipcode'    => $client['zipcode'],
    ':city'       => $client['city'],
    ':state'      => $client['state'],
    ':country'    => $client['country'] > 0 ? $client['country'] : null,
    ':telephone'  => $client['telephone'],
    ':pswrd'      => password_hash($client['pswrd'], PASSWORD_DEFAULT),
]);

render_header('Welkom!');
?>
<main class="centering">
    <h2>Welkom, <?php echo h($client['first_name']); ?>!</h2>
    <p>Uw registratie is succesvol afgerond. U kunt nu inloggen met uw e-mailadres en wachtwoord.</p>
    <p>
        <a href="login.php">Ga naar de loginpagina</a> |
        <a href="index.php">Terug naar home</a>
    </p>
</main>
<?php render_footer(); ?>
