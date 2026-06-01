<!DOCTYPE html>
<html lang="nl">
<head>
	<meta charset="UTF-8">
	<title>Verwijder bestellingen (beheer)</title>
	<link rel="stylesheet" type="text/css" href="company.css">
</head>
<body>
<?php
session_start();
require_once "dbconnect.php";

if (!isset($_SESSION["benJeErAl"]) || $_SESSION["benJeErAl"] !== true || !isset($_SESSION["SoortToegang"]) || $_SESSION["SoortToegang"] !== "Beheer") {
	echo "<h2>Alleen voor ingelogde beheerders</h2>";
	exit();
}

echo "<header class='spacebelowabove'>";
echo "<h1>Verwijder bestellingen</h1>";
include "nav.html";
echo "</header>";

echo "<main class='centering'>";
try {
	$sQuery = "SELECT pu.ID AS purchaseID, c.last_name, pu.purchasedate, pl.ID AS plID, pr.productname, pl.quantity FROM purchase pu JOIN client c ON pu.clientid = c.id JOIN purchaseline pl ON pl.purchaseid = pu.ID JOIN product pr ON pl.productid = pr.ID WHERE pu.delivered = 0 ORDER BY pu.purchasedate";
	$oStmt = $db->prepare($sQuery);
	$oStmt->execute();

	echo "<table class='tabledisp'><thead><tr><th>purchase.ID</th><th>client.last_name</th><th>purchasedate</th><th>purchaseline.ID</th><th>product</th><th>quantity</th><th>Acties</th></tr></thead><tbody>";
	while ($aRow = $oStmt->fetch(PDO::FETCH_ASSOC)) {
		echo "<tr>";
		echo "<td>" . htmlspecialchars($aRow['purchaseID']) . "</td>";
		echo "<td>" . htmlspecialchars($aRow['last_name']) . "</td>";
		echo "<td>" . htmlspecialchars($aRow['purchasedate']) . "</td>";
		echo "<td>" . htmlspecialchars($aRow['plID']) . "</td>";
		echo "<td>" . htmlspecialchars($aRow['productname']) . "</td>";
		echo "<td>" . htmlspecialchars($aRow['quantity']) . "</td>";
		echo "<td>";
		echo "<form action='pur-crud-delete.php' method='post' style='display:inline-block;margin-right:.5rem;'>";
		echo "<input type='hidden' name='action' value='regel'>";
		echo "<input type='hidden' name='purchaseid' value='".htmlspecialchars($aRow['purchaseID'])."'>";
		echo "<input type='hidden' name='purchaselineid' value='".htmlspecialchars($aRow['plID'])."'>";
		echo "<input type='submit' value='Regel'>";
		echo "</form>";

		echo "<form action='pur-crud-delete.php' method='post' style='display:inline-block;'>";
		echo "<input type='hidden' name='action' value='aankoop'>";
		echo "<input type='hidden' name='purchaseid' value='".htmlspecialchars($aRow['purchaseID'])."'>";
		echo "<input type='submit' value='Aankoop'>";
		echo "</form>";
		echo "</td>";
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