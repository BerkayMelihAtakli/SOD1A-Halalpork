<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';

$isAdmin = isset($_SESSION['SoortToegang']) && $_SESSION['SoortToegang'] === 'Beheer';

$errors = [];
$client = validate_client_input($errors, true);

if (!empty($errors)) {
    $_SESSION['client_errors'] = $errors;
    $_SESSION['old_client'] = $_POST;
    header('Location: cli-crud-add.php');
    exit();
}

$checkEmail = $db->prepare("SELECT COUNT(*) FROM client WHERE email = :email");
$checkEmail->execute([':email' => $client['email']]);
if ((int)$checkEmail->fetchColumn() > 0) {
    $_SESSION['client_errors'] = ['Dit e-mailadres is al in gebruik.'];
    $_SESSION['old_client'] = $_POST;
    header('Location: cli-crud-add.php');
    exit();
}

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

if ($isAdmin) {
    header('Location: cli-crud-get.php?msg=' . urlencode('Klant succesvol toegevoegd.'));
} else {
    $newId = (int)$db->lastInsertId();
    session_regenerate_id(true);
    $_SESSION['benJeErAl']       = true;
    $_SESSION['welkNummerIsDit'] = $newId;
    $_SESSION['wieBenJeDan']     = trim($client['first_name'] . ' ' . $client['last_name']);
    $_SESSION['SoortToegang']    = 'Klant';
    header('Location: index.php?msg=' . urlencode('Welkom! Je account is aangemaakt.'));
}
exit();
?>
