<?php
session_start();
require_once "dbconnect.php";
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>C03-01 Overzicht producten + totale waarde aankopen</title>
<link rel="stylesheet" type="text/css" href="company.css">
</head>
<body>
<header class='spacebelowabove'>
<h1>Overzicht producten + totale waarde aankopen</h1>
<?php if (file_exists("nav.html")) include "nav.html"; ?>
</header>
<main class='centering'>
<table class='crud'>
<tr><th>Product</th><th>Aantal verkocht</th><th>Totale waarde</th></tr>
<?php
$sql = "SELECT p.productname,
SUM(pl.quantity) AS totaal_aantal,
SUM(pl.quantity * pl.price) AS totale_waarde
FROM product p
LEFT JOIN purchaseline pl ON p.ID = pl.productid
GROUP BY p.ID, p.productname
ORDER BY totale_waarde DESC";
$stmt = $db->query($sql);
foreach($stmt as $row){
echo "<tr>";
echo "<td>".htmlspecialchars($row['productname'])."</td>";
echo "<td>".($row['totaal_aantal'] ?? 0)."</td>";
echo "<td>€ ".number_format(($row['totale_waarde'] ?? 0),2,',','.')."</td>";
echo "</tr>";
}
?>
</table>
</main>
</body>
</html>