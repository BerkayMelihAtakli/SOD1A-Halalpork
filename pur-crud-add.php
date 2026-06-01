<!DOCTYPE html>
<html lang="nl">
<head>
	<meta charset="UTF-8">
	<title>Bestellen - Productoverzicht</title>
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


$_SESSION["chk_pur_add"] = true;

echo "<header class='spacebelowabove'>";
echo "<h1>Producten bestellen</h1>";
include "nav.html";
echo "</header>";

echo "<main class='centering'>";
echo "<h2>LET OP: je kan maar één product tegelijk bestellen</h2>";

try {
	$sQuery = "SELECT p.ID, p.productname, c.name AS category, p.price FROM product p JOIN category c ON p.categoryid = c.ID WHERE p.isactive = 'J' ORDER BY p.productname";
	$oStmt = $db->prepare($sQuery);
	$oStmt->execute();

	echo "<table class='tabledisp'><thead><tr><th>ID</th><th>productname</th><th>category</th><th>price</th><th>Aantal</th><th>Actie</th></tr></thead><tbody>";
	while ($aRow = $oStmt->fetch(PDO::FETCH_ASSOC)) {
		echo "<tr>";
		echo "<td>" . htmlspecialchars($aRow['ID']) . "</td>";
		echo "<td>" . htmlspecialchars($aRow['productname']) . "</td>";
		echo "<td>" . htmlspecialchars($aRow['category']) . "</td>";
		echo "<td>&euro; " . number_format($aRow['price'],2,',','.') . "</td>";
		echo "<td>";
		echo "<form action='pur-crud-adding.php' method='post'>";
		echo "<input type='hidden' name='productid' value='" . htmlspecialchars($aRow['ID']) . "'>";
		echo "<input type='hidden' name='price' value='" . htmlspecialchars($aRow['price']) . "'>";
		echo "<input type='number' name='quantity' value='1' min='1' required>";
		echo "</td>";
		echo "<td>";
		echo "<input type='hidden' name='submt-pur-add' value='1'>";
		echo "<input type='submit' value='Bestellen'>";
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