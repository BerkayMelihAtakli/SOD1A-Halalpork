<?php
session_start();
require_once 'dbconnect.php';
require_once 'category_country_helpers.php';
require_admin();
$id = (int)($_POST['country_id'] ?? 0);
if ($id <= 0 || country_is_used_by_product($db, $id)) {
    header('Location: cou-crud-get.php?msg=' . urlencode('Country kan niet verwijderd worden.'));
    exit();
}
$stmt = $db->prepare('DELETE FROM country WHERE idcountry = ?');
$stmt->execute([$id]);
header('Location: cou-crud-get.php?msg=' . urlencode('Country verwijderd.'));
exit();
?>
