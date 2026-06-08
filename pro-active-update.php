<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

require_admin();

$id = (int)($_POST['product_id'] ?? 0);

if (!isset($_SESSION['active_product_id']) || (int)$_SESSION['active_product_id'] !== $id) {
    header('Location: pro-active-get.php?msg=' . urlencode('Ongeldige wijziging: product-ID klopt niet.'));
    exit();
}

$stmt = $db->prepare("SELECT ID, productname, isactive FROM product WHERE ID = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: pro-active-get.php?msg=' . urlencode('Product niet gevonden.'));
    exit();
}

if ($product['isactive'] === 'J') {
    $newStatus = 'N';
    $msg = 'Product is gedeactiveerd.';
} else {
    $newStatus = 'J';
    $msg = 'Product is geactiveerd.';
}

$update = $db->prepare("UPDATE product SET isactive = ? WHERE ID = ?");
$update->execute([$newStatus, $id]);

unset($_SESSION['active_product_id']);

header('Location: pro-active-get.php?msg=' . urlencode($msg));
exit();
?>
