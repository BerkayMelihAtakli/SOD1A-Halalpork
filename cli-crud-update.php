<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';

if (!isset($_SESSION['pending_update']) || !isset($_SESSION['update_client_id'])) {
    header('Location: cli-crud-upd.php');
    exit();
}

if (!is_admin() && !is_client()) {
    header('Location: login.php');
    exit();
}

$client = $_SESSION['pending_update'];
$id = (int)$_SESSION['update_client_id'];

if ((int)$client['id'] !== $id) {
    header('Location: cli-crud-get.php?msg=' . urlencode('Ongeldige wijziging: klant-ID klopt niet.'));
    exit();
}

unset($_SESSION['pending_update']);
unset($_SESSION['update_client_id']);

$sql = "UPDATE client
        SET first_name = :first_name,
            last_name  = :last_name,
            email      = :email,
            adress     = :adress,
            zipcode    = :zipcode,
            city       = :city,
            state      = :state,
            country    = :country,
            telephone  = :telephone
        WHERE id = :id";
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
    ':id'         => $id,
]);

if (is_admin()) {
    header('Location: cli-crud-get.php?msg=' . urlencode('Klantgegevens succesvol gewijzigd.'));
    exit();
}

render_header('Gegevens gewijzigd');
?>
<main class="centering">
    <h2>Gegevens succesvol gewijzigd</h2>
    <p>Uw gegevens zijn bijgewerkt.</p>
    <p><a href="index.php">Terug naar home</a></p>
</main>
<?php render_footer(); ?>
