<!DOCTYPE html>
<html lang="nl">
<head>
	<meta charset="UTF-8">
	<title>Bestelling verwerking</title>
	<link rel="stylesheet" type="text/css" href="company.css">
</head>
<body>
<?php
session_start();
require_once "dbconnect.php";

if (!isset($_POST['submt-pur-add']) || !isset($_SESSION['chk_pur_add'])) {
	echo "<main class='centering'><h2>Niet op de juiste manier gekomen.</h2><p><a href='pur-crud-add.php'>Terug naar bestellen</a></p></main></body></html>";
	exit();
}

if (!isset($_SESSION["benJeErAl"]) || $_SESSION["benJeErAl"] !== true || !isset($_SESSION["SoortToegang"]) || $_SESSION["SoortToegang"] !== "Klant") {
	echo "<main class='centering'><h2>Alleen voor ingelogde klanten</h2></main></body></html>";
	exit();
}

if (empty($_SESSION['welkNummerIsDit']) || (int)$_SESSION['welkNummerIsDit'] <= 0) {
	echo "<main class='centering'><h2>Ongeldig klantaccount. Log opnieuw in.</h2><p><a href='login.php'>Inloggen</a></p></main></body></html>";
	exit();
}

$productid = intval($_POST['productid'] ?? 0);
$quantity  = intval($_POST['quantity'] ?? 0);

if ($productid <= 0 || $quantity < 1) {
	echo "<main class='centering'><h2>Ongeldige productgegevens</h2><p><a href='pur-crud-add.php'>Terug</a></p></main></body></html>";
	exit();
}

echo "<header class='spacebelowabove'>";
echo "<h1>Bestelling geplaatst</h1>";
include "nav.html";
echo "</header>";
echo "<main class='centering'>";

try {
	$sCheck = "SELECT price FROM product WHERE ID = :productid AND isactive = 'J'";
	$oCheck = $db->prepare($sCheck);
	$oCheck->bindValue(':productid', $productid);
	$oCheck->execute();
	$aCheck = $oCheck->fetch(PDO::FETCH_ASSOC);

	if (!$aCheck) {
		echo "<h2>Product niet beschikbaar</h2><p><a href='pur-crud-add.php'>Terug</a></p>";
		echo "</main></body></html>";
		exit();
	}

	$price = floatval($aCheck['price']);

	if (!isset($_SESSION['purchase_id']) || empty($_SESSION['purchase_id'])) {
		$sIns = "INSERT INTO purchase (clientid, purchasedate, delivered) VALUES (:clientid, :purchasedate, 0)";
		$oIns = $db->prepare($sIns);
		$oIns->bindValue(':clientid', $_SESSION['welkNummerIsDit']);
		$oIns->bindValue(':purchasedate', date('Y-m-d'));
		$oIns->execute();
		$_SESSION['purchase_id'] = $db->lastInsertId();
	}

	unset($_SESSION['chk_pur_add']);

	$sPL = "INSERT INTO purchaseline (purchaseid, productid, price, quantity) VALUES (:purchaseid, :productid, :price, :quantity)";
	$oPL = $db->prepare($sPL);
	$oPL->bindValue(':purchaseid', $_SESSION['purchase_id']);
	$oPL->bindValue(':productid', $productid);
	$oPL->bindValue(':price', $price);
	$oPL->bindValue(':quantity', $quantity);
	$oPL->execute();

	echo "<h2>Bestelling is opgeslagen</h2>";
	echo "<p>Je kan een nieuw product aan de bestelling toevoegen.</p>";
	echo "<p><a href='pur-crud-add.php'>Terug naar producten</a></p>";

} catch (PDOException $e) {
	$sMsg = '<p>Regelnummer: '.$e->getLine().'<br />Bestand: '.$e->getFile().'<br />Foutmelding: '.$e->getMessage().'</p>';
	trigger_error($sMsg);
}

echo "</main></body></html>";

