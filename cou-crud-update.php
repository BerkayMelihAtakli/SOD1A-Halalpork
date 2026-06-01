<?php
session_start();
require_once 'dbconnect.php';
require_once 'category_country_helpers.php';
require_admin();
$id = (int)($_POST['country_id'] ?? 0);
$errors = [];
$country = validate_country_input($errors);
if (!$errors && country_field_exists($db, 'name', $country['name'], $id)) {
    $errors[] = 'Deze landnaam bestaat al.';
}
if (!$errors && country_field_exists($db, 'code', $country['code'], $id)) {
    $errors[] = 'Deze landcode bestaat al.';
}
if ($errors) {
    header('Location: cou-crud-upd.php?id=' . $id . '&error=' . urlencode(implode(' ', $errors)));
    exit();
}
$stmt = $db->prepare('UPDATE country SET name = ?, code = ? WHERE idcountry = ?');
$stmt->execute([$country['name'], $country['code'], $id]);
header('Location: cou-crud-get.php?msg=' . urlencode('Country gewijzigd.'));
exit();
?>
