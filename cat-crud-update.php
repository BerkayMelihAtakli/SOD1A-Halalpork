<?php
session_start();
require_once 'dbconnect.php';
require_once 'category_country_helpers.php';
require_admin();
$id = (int)($_POST['category_id'] ?? 0);
$errors = [];
$name = validate_category_input($errors);
if (!$errors && category_name_exists($db, $name, $id)) {
    $errors[] = 'Deze categorie bestaat al.';
}
if ($errors) {
    header('Location: cat-crud-upd.php?id=' . $id . '&error=' . urlencode(implode(' ', $errors)));
    exit();
}
$stmt = $db->prepare('UPDATE category SET name = ? WHERE ID = ?');
$stmt->execute([$name, $id]);
header('Location: cat-crud-get.php?msg=' . urlencode('Categorie gewijzigd.'));
exit();
?>
