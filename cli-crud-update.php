<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';
require_admin();

$id = (int)($_POST['client_id'] ?? 0);
if (!isset($_SESSION['update_client_id']) || (int)$_SESSION['update_client_id'] !== $id) {
    header('Location: cli-crud-get.php?msg=' . urlencode('Ongeldige wijziging: klant-ID klopt niet.'));
    exit();
}

$errors = [];
$client = validate_client_input($errors, false);

if (!empty($errors)) {
    $_SESSION['client_errors'] = $errors;
    $_SESSION['old_client'] = $_POST;
    header('Location: cli-crud-upd.php?id=' . $id);
    exit();
}

if ($client['pswrd'] !== '') {
    if (strlen($client['pswrd']) < 6) {
        $_SESSION['client_errors'] = ['Nieuw wachtwoord moet minimaal 6 tekens bevatten.'];
        $_SESSION['old_client'] = $_POST;
        header('Location: cli-crud-upd.php?id=' . $id);
        exit();
    }
    $sql = "UPDATE client
            SET first_name = :first_name, last_name = :last_name, email = :email,
                adress = :adress, zipcode = :zipcode, city = :city, state = :state,
                country = :country, telephone = :telephone, pswrd = :pswrd
            WHERE id = :id";
    $params = [
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
        ':id'         => $id,
    ];
} else {
    $sql = "UPDATE client
            SET first_name = :first_name, last_name = :last_name, email = :email,
                adress = :adress, zipcode = :zipcode, city = :city, state = :state,
                country = :country, telephone = :telephone
            WHERE id = :id";
    $params = [
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
    ];
}

$stmt = $db->prepare($sql);
$stmt->execute($params);
unset($_SESSION['update_client_id']);

header('Location: cli-crud-get.php?msg=' . urlencode('Klant succesvol gewijzigd.'));
exit();
?>
