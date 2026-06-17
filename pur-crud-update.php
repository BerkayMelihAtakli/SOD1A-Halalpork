<!DOCTYPE html>
<html lang="nl">
<head>
	<meta charset="UTF-8">
	<title>Wijziging opgeslagen</title>
	<link rel="stylesheet" type="text/css" href="company.css">
</head>
<body>
<?php
session_start();
require_once "dbconnect.php";

if (!isset($_SESSION["benJeErAl"]) || $_SESSION["benJeErAl"] !== true || !isset($_SESSION["SoortToegang"]) || $_SESSION["SoortToegang"] !== "Klant") {
	echo "<main class='centering'><h2>Alleen voor ingelogde klanten</h2></main></body></html>";
	exit();
}

if (!isset($_POST['plID']) || !isset($_POST['quantity'])) {
	echo "<main class='centering'><p>Geen gegevens ontvangen.</p><p><a href='pur-crud-upd.php'>Terug</a></p></main></body></html>";
	exit();
}

$plID     = intval($_POST['plID']);
$quantity = intval($_POST['quantity']);

if ($quantity < 1) {
	echo "<main class='centering'><p>Aantal moet minimaal 1 zijn.</p><p><a href='pur-crud-upd.php'>Terug</a></p></main></body></html>";
	exit();
}

echo "<header class='spacebelowabove'>";
echo "<h1>Wijziging bestelling</h1>";
include "nav.html";
echo "</header>";
echo "<main class='centering'>";

try {
	$sUpd = "UPDATE purchaseline pl INNER JOIN purchase pu ON pl.purchaseid = pu.ID SET pl.quantity = :quantity WHERE pl.ID = :plID AND pu.clientid = :clientid AND pu.delivered = 0";
	$oUpd = $db->prepare($sUpd);
	$oUpd->bindValue(':quantity', $quantity);
	$oUpd->bindValue(':plID', $plID);
	$oUpd->bindValue(':clientid', $_SESSION['welkNummerIsDit']);
	$oUpd->execute();

	if ($oUpd->rowCount() === 0) {
		echo "<h2>Wijziging niet opgeslagen</h2>";
		echo "<p>De bestelling is mogelijk niet meer wijzigbaar of behoort niet tot u.</p>";
	} else {
		echo "<h2>Wijziging opgeslagen</h2>";
		echo "<p>De wijziging is doorgevoerd.</p>";
	}
	echo "<p><a href='pur-crud-upd.php'>Terug naar wijzigen</a></p>";

} catch (PDOException $e) {
	$sMsg = '<p>Regelnummer: '.$e->getLine().'<br />Bestand: '.$e->getFile().'<br />Foutmelding: '.$e->getMessage().'</p>';
	trigger_error($sMsg);
}

echo "</main></body></html>";
