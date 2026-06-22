<!DOCTYPE html>
<html lang="nl"> 
<head>
	 <meta charset="UTF-8">
	 <title>Bread Company</title>
	 <link rel="stylesheet" type="text/css" href="company.css">  
</head>

<body>
	<header>
		<h1>Welkom bij de Bread Company</h1>
		<!-- hieronder wordt het menu opgehaald. -->
		<?php
			session_start(); 
			include "nav.html";
		?>
	</header>
 	<?php
	require_once "dbconnect.php";
	try {
		$sQuery = "SELECT purchase.ID AS Aankoopnr, 
				client.first_name AS voornaam, 
				client.last_name AS achternaam, 
				client.city AS woonplaats, 
				purchasedate, 
				delivered, 
				SUM(purchaseline.quantity*purchaseline.price) AS TotAankoop
			FROM purchase
			INNER JOIN client ON purchase.clientid = client.id
			INNER JOIN purchaseline ON purchase.ID = purchaseline.purchaseid
			GROUP BY Aankoopnr
			ORDER BY Aankoopnr";
		$oStmt = $db->prepare($sQuery);
		$oStmt->execute();

		echo "<p>&nbsp;</p><h2 class='centercell'>Overzicht totaalprijs per aankoop</h2><p>&nbsp;</p>";
		if ($oStmt->rowCount() > 0) {
			echo '<div class="centerflex"><table class="tabledisp2">';
			echo '<thead>';
			echo '<td>Aankoopnr.</td>';
			echo '<td>Voornaam</td>';
			echo '<td>Achternaam</td>';
			echo '<td>Woonplaats</td>';
			echo '<td>Aankoopdatum</td>';
			echo '<td>Geleverd</td>';
			echo '<td>Totaal aankoop</td>';
			echo '</thead>';
			while ($aRow = $oStmt->fetch(PDO::FETCH_ASSOC)) {
				echo '<tr>';
				echo '<td>' . $aRow['Aankoopnr'] . '</td>';
				echo '<td>' . $aRow['voornaam'] . '</td>';
				echo '<td>' . $aRow['achternaam'] . '</td>';
				echo '<td>' . $aRow['woonplaats'] . '</td>';
				echo '<td>' . $aRow['purchasedate'] . '</td>';
				echo '<td>' . $aRow['delivered'] . '</td>';
				echo '<td>€ ' . number_format($aRow['TotAankoop'], 2, ',', '.') . '</td>';
				echo '</tr>';
			}
			echo '</table></div>';
		} else {
			echo 'Helaas, geen gegevens bekend';
		}
	} catch (PDOException $e) {
		$sMsg = '<p> 
					Regelnummer: ' . $e->getLine() . '<br /> 
					Bestand: ' . $e->getFile() . '<br /> 
					Foutmelding: ' . $e->getMessage() . ' 
				</p>';

		trigger_error($sMsg);
	}
	$db = null;
	?>


</body>
</html>