<!DOCTYPE html>
<html lang="nl">
<head>
	<meta charset="UTF-8">
	<title>Wijzig bestellingen</title>
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
echo "<h1>Wijzig mijn bestellingen</h1>";
include "nav.html";
echo "</header>";

echo "<main class='centering'>";
try {
	$sQuery = "SELECT pl.ID AS plID, pu.ID AS purchaseID, pr.productname, pl.quantity, pu.delivered FROM purchaseline pl JOIN purchase pu ON pl.purchaseid = pu.ID JOIN product pr ON pl.productid = pr.ID WHERE pu.clientid = :clientid AND pu.delivered = 0 ORDER BY pu.purchasedate DESC";
	$oStmt = $db->prepare($sQuery);
	$oStmt->bindValue(':clientid', $_SESSION['welkNummerIsDit']);
	$oStmt->execute();

	echo "<form action='pur-crud-upd01.php' method='post'>";
	echo "<table class='tabledisp'><thead><tr><th>Bestelnummer</th><th>Product</th><th>Huidig aantal</th><th>Nieuw aantal</th><th>Actie</th></tr></thead><tbody>";
	while ($aRow = $oStmt->fetch(PDO::FETCH_ASSOC)) {
		echo "<tr>";
		echo "<td>" . htmlspecialchars($aRow['purchaseID']) . "</td>";
		echo "<td>" . htmlspecialchars($aRow['productname']) . "</td>";
		echo "<td>" . htmlspecialchars($aRow['quantity']) . "</td>";
		echo "<td><input type='number' name='quantity[".htmlspecialchars($aRow['plID'])."]' value='".htmlspecialchars($aRow['quantity'])."' min='1' required></td>";
		echo "<td><button type='submit' name='save_pl' value='".htmlspecialchars($aRow['plID'])."'>Opslaan</button></td>";
		echo "</tr>";
	}
	echo "</tbody></table>";
	echo "</form>";

} catch (PDOException $e) {
	$sMsg = '<p>Regelnummer: '.$e->getLine().'<br />Bestand: '.$e->getFile().'<br />Foutmelding: '.$e->getMessage().'</p>';
	trigger_error($sMsg);
}

echo "</main>";
?>
</body>
</html>