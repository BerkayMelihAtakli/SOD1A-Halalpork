<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
require_admin();

$id = (int)($_POST['product_id'] ?? 0);
if (!isset($_SESSION['delete_product_id']) || (int)$_SESSION['delete_product_id'] !== $id) {
    header('Location: pro-crud-get.php?msg=' . urlencode('Ongeldige verwijdering: product-ID klopt niet.'));
    exit();
}

$check = $db->prepare("SELECT COUNT(*) FROM purchaseline pl INNER JOIN purchase pu ON pl.purchaseid = pu.ID WHERE pl.productid = :id AND pu.delivered = 0");
$check->execute([':id' => $id]);
if ((int)$check->fetchColumn() > 0) {
    header('Location: pro-crud-get.php?msg=' . urlencode('Product kan niet verwijderd worden: niet-afgeleverde bestelling gevonden.'));
    exit();
}

$stmt = $db->prepare('DELETE FROM product WHERE ID = :id');
$stmt->execute([':id' => $id]);
unset($_SESSION['delete_product_id']);
header('Location: pro-crud-get.php?msg=' . urlencode('Product succesvol verwijderd.'));
exit();
?>
