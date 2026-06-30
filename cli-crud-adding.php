<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['reg_client'])) {
    header('Location: cli-crud-add.php');
    exit();
}

$c = $_SESSION['reg_client'];
unset($_SESSION['reg_client']);

$sql = "INSERT INTO client (first_name, last_name, email, adress, zipcode, city, state, country, telephone, isadmin, pswrd)
        VALUES (:first_name, :last_name, :email, :adress, :zipcode, :city, :state, :country, :telephone, 'N', :pswrd)";
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
    ':pswrd'      => $c['pswrd_hash'],
]);

$isAdmin = isset($_SESSION['SoortToegang']) && $_SESSION['SoortToegang'] === 'Beheer';

if ($isAdmin) {
    header('Location: cli-crud-get.php?msg=' . urlencode('Klant succesvol toegevoegd.'));
} else {
    $newId = (int)$db->lastInsertId();
    session_regenerate_id(true);
    $_SESSION['benJeErAl']       = true;
    $_SESSION['welkNummerIsDit'] = $newId;
    $_SESSION['wieBenJeDan']     = trim($c['first_name'] . ' ' . $c['last_name']);
    $_SESSION['SoortToegang']    = 'Klant';
    header('Location: index.php?msg=' . urlencode('Welkom ' . $c['first_name'] . '! Je account is aangemaakt.'));
}
exit();
?>
