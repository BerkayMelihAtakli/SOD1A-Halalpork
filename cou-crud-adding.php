<?php
session_start();
require_once 'dbconnect.php';
require_once 'category_country_helpers.php';
require_admin();
$errors = [];
$country = validate_country_input($errors);
if (!$errors && country_field_exists($db, 'name', $country['name'])) {
    $errors[] = 'Deze landnaam bestaat al.';
}
if (!$errors && country_field_exists($db, 'code', $country['code'])) {
    $errors[] = 'Deze landcode bestaat al.';
}
if ($errors) {
    header('Location: cou-crud-add.php?error=' . urlencode(implode(' ', $errors)));
    exit();
}
$stmt = $db->prepare('INSERT INTO country (name, code) VALUES (?, ?)');
$stmt->execute([$country['name'], $country['code']]);
header('Location: cou-crud-get.php?msg=' . urlencode('Country toegevoegd.'));
exit();
?>
