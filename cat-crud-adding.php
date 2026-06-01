<?php
session_start();
require_once 'dbconnect.php';
require_once 'category_country_helpers.php';
require_admin();
$errors = [];
$name = validate_category_input($errors);
if (!$errors && category_name_exists($db, $name)) {
    $errors[] = 'Deze categorie bestaat al.';
}
if ($errors) {
    header('Location: cat-crud-add.php?error=' . urlencode(implode(' ', $errors)));
    exit();
}
$stmt = $db->prepare('INSERT INTO category (name) VALUES (?)');
$stmt->execute([$name]);
header('Location: cat-crud-get.php?msg=' . urlencode('Categorie toegevoegd.'));
exit();
?>
