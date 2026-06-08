<!DOCTYPE html>
<html lang="nl">
<head>
	<meta charset="UTF-8">
	<title>Mijn bestellingen</title>
	<link rel="stylesheet" type="text/css" href="company.css">
</head>
<body>
<?php
session_start();
require_once "dbconnect.php";

if (!isset($_SESSION["benJeErAl"]) || $_SESSION["benJeErAl"] !== true || !isset($_SESSION["SoortToegang"]) || $_SESSION["SoortToegang"] !== "Klant") {
	echo "<h2>Alleen voor ingelogde klanten</h2>";
	exit();
}

echo "<header class='spacebelowabove'>";
echo "<h1>Mijn bestellingen</h1>";
include "nav.html";
echo "</header>";

echo "<main class='centering'>";
try {
	$sQuery = "SELECT pu.ID AS purchaseID, pu.purchasedate, pu.delivered, pr.productname, pl.price, pl.quantity FROM purchase pu JOIN purchaseline pl ON pu.ID = pl.purchaseid JOIN product pr ON pr.ID = pl.productid WHERE pu.clientid = :clientid ORDER BY pu.purchasedate DESC";
	$oStmt = $db->prepare($sQuery);
	$oStmt->bindValue(':clientid', $_SESSION['welkNummerIsDit']);
	$oStmt->execute();

	echo "<table class='tabledisp'><thead><tr><th>Bestelnummer</th><th>Besteldatum</th><th>Afgeleverd</th><th>Productnaam</th><th>Prijs</th><th>Aantal</th></tr></thead><tbody>";
	while ($aRow = $oStmt->fetch(PDO::FETCH_ASSOC)) {
		echo "<tr>";
		echo "<td>" . htmlspecialchars($aRow['purchaseID']) . "</td>";
		echo "<td>" . htmlspecialchars($aRow['purchasedate']) . "</td>";
		echo "<td>" . ($aRow['delivered'] ? 'Ja' : 'Nee') . "</td>";
		echo "<td>" . htmlspecialchars($aRow['productname']) . "</td>";
		echo "<td>&euro; " . number_format($aRow['price'],2,',','.') . "</td>";
		echo "<td>" . htmlspecialchars($aRow['quantity']) . "</td>";
		echo "</tr>";
	}
	echo "</tbody></table>";
} catch (PDOException $e) {
	$sMsg = '<p>Regelnummer: '.$e->getLine().'<br />Bestand: '.$e->getFile().'<br />Foutmelding: '.$e->getMessage().'</p>';
	trigger_error($sMsg);
}
echo "</main>";
?>
</body>
</html>