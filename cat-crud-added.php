<?php include "underconstruct.php"; ?>

<?php
include 'dbconnect.php';

$name = trim($_POST['name']);


if (empty($name) || !preg_match("/^[a-zA-Z ]*$/", $name)) {
    die("Fout: Naam is verplicht en mag alleen letters en spaties bevatten. <a href='cat-crud-add.php'>Terug</a>");
}

// Controle 2: Bestaat de naam al?
$stmt = $pdo->prepare("SELECT * FROM category WHERE name = ?");
$stmt->execute([$name]);
if ($stmt->fetch()) {
    die("Fout: Deze categorie naam bestaat al! <a href='cat-crud-add.php'>Terug</a>");
}


$stmt = $pdo->prepare("INSERT INTO category (name) VALUES (?)");
$stmt->execute([$name]);

header("Location: cat-crud-get.php");
exit();
?>