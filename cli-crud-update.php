<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST'
    || empty($_SESSION['upd_client_id'])
    || empty($_SESSION['upd_client_data'])) {
    header('Location: cli-crud-upd.php');
    exit();
}

$id     = (int)$_SESSION['upd_client_id'];
$c      = $_SESSION['upd_client_data'];
$isAdminEdit = $_SESSION['upd_from_admin'] ?? false;
unset($_SESSION['upd_client_id'], $_SESSION['upd_client_data'], $_SESSION['upd_from_admin']);

$sql = "UPDATE client
        SET first_name = :first_name, last_name = :last_name, email = :email,
            adress = :adress, zipcode = :zipcode, city = :city, state = :state,
            country = :country, telephone = :telephone
        WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->execute([
    ':first_name' => $c['first_name'],
    ':last_name'  => $c['last_name'],
    ':email'      => $c['email'],
    ':adress'     => $c['adress'],
    ':zipcode'    => $c['zipcode'],
    ':city'       => $c['city'],
    ':state'      => $c['state'],
    ':country'    => $c['country'] > 0 ? $c['country'] : null,
    ':telephone'  => $c['telephone'],
    ':id'         => $id,
]);

render_header('Gegevens gewijzigd');
?>
<main class="centering">
    <h2>Gegevens succesvol gewijzigd</h2>
    <p>Jouw gegevens zijn bijgewerkt.</p>
    <?php if ($isAdminEdit): ?>
        <p><a href="cli-crud-get.php"><button type="button">Terug naar klantenlijst</button></a></p>
    <?php else: ?>
        <p><a href="index.php"><button type="button">Terug naar home</button></a></p>
    <?php endif; ?>
</main>
<?php render_footer(); ?>
