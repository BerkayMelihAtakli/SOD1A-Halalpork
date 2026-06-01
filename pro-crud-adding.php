<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
require_admin();

$errors = [];
$product = validate_product_input($errors);

if (!empty($errors)) {
    $_SESSION['product_errors'] = $errors;
    $_SESSION['old_product'] = $_POST;
    header('Location: pro-crud-add.php');
    exit();
}

$sql = "INSERT INTO product (productname, ingredients, allergens, price, categoryid, supplierid, isactive)
        VALUES (:productname, :ingredients, :allergens, :price, :categoryid, :supplierid, 'J')";
$stmt = $db->prepare($sql);
$stmt->execute([
    ':productname' => $product['productname'],
    ':ingredients' => $product['ingredients'],
    ':allergens' => $product['allergens'],
    ':price' => $product['price'],
    ':categoryid' => $product['categoryid'],
    ':supplierid' => $product['supplierid'],
]);

header('Location: pro-crud-get.php?msg=' . urlencode('Product succesvol toegevoegd.'));
exit();
?>
