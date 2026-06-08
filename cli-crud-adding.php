<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';
require_admin();

$errors = [];
$client = validate_client_input($errors, true);

if (!empty($errors)) {
    $_SESSION['client_errors'] = $errors;
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

header('Location: cli-crud-get.php?msg=' . urlencode('Klant succesvol toegevoegd.'));
exit();
?>
