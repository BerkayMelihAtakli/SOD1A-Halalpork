<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
require_admin();

$id = (int)($_POST['product_id'] ?? 0);
if (!isset($_SESSION['update_product_id']) || (int)$_SESSION['update_product_id'] !== $id) {
    header('Location: pro-crud-get.php?msg=' . urlencode('Ongeldige wijziging: product-ID klopt niet.'));
    exit();
}

$errors = [];
$product = validate_product_input($errors);
if (!empty($errors)) {
    $_SESSION['product_errors'] = $errors;
    $_SESSION['old_product'] = $_POST;
    header('Location: pro-crud-upd.php?id=' . $id);
    exit();
}

$sql = "UPDATE product
        SET productname = :productname,
            ingredients = :ingredients,
            allergens = :allergens,
            price = :price,
            categoryid = :categoryid,
            supplierid = :supplierid
        WHERE ID = :id";
$stmt = $db->prepare($sql);
$stmt->execute([
    ':productname' => $product['productname'],
    ':ingredients' => $product['ingredients'],
    ':allergens' => $product['allergens'],
    ':price' => $product['price'],
    ':categoryid' => $product['categoryid'],
    ':supplierid' => $product['supplierid'],
    ':id' => $id,
]);
unset($_SESSION['update_product_id']);
header('Location: pro-crud-get.php?msg=' . urlencode('Product succesvol gewijzigd.'));
exit();
?>
