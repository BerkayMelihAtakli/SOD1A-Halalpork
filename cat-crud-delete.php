<?php
session_start();
require_once 'dbconnect.php';
require_once 'category_country_helpers.php';
require_admin();
$id = (int)($_POST['category_id'] ?? 0);
if ($id <= 0 || category_is_used($db, $id)) {
    header('Location: cat-crud-get.php?msg=' . urlencode('Categorie kan niet verwijderd worden.'));
    exit();
}
$stmt = $db->prepare('DELETE FROM category WHERE ID = ?');
$stmt->execute([$id]);
header('Location: cat-crud-get.php?msg=' . urlencode('Categorie verwijderd.'));
exit();
?>
