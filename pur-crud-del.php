<!DOCTYPE html>
<html lang="nl"> 
<head>
	 <meta charset="UTF-8">
	 <title>Bestellingen beheren</title>
	 <link rel="stylesheet" type="text/css" href="company.css">  
</head>

<body>
    <?php
        session_start();

        if (!isset($_SESSION["benJeErAl"]) || $_SESSION["SoortToegang"] !== "Beheer") {
            header("Refresh: 4, url=index.php");
            echo "<h2>Je moet ingelogd zijn als beheerder!</h2>";
            exit();
        }

        echo "<header class='spacebelowabove'>";
        echo "<h1>Bestellingen beheren</h1>";
        include "nav.html";
        echo "</header>";
    ?>

    <main class="centering">
        <h2 class="spacebelowabove">Openstaande bestellingen (niet afgeleverd)</h2>

        <?php
            require_once "dbconnect.php";

            try {
                $sQuery = "SELECT purchase.ID AS purchase_id,
                                  client.last_name AS achternaam,
                                  purchase.purchasedate AS besteldatum,
                                  purchaseline.ID AS line_id,
                                  product.productname AS productnaam,
                                  purchaseline.quantity AS aantal
                           FROM purchase
                           JOIN client ON client.id = purchase.clientid
                           JOIN purchaseline ON purchaseline.purchaseid = purchase.ID
                           JOIN product ON product.ID = purchaseline.productid
                           WHERE purchase.delivered = 0
                           ORDER BY purchase.ID ASC";

                $oStmt = $db->prepare($sQuery);
                $oStmt->execute();

                if ($oStmt->rowCount() > 0) {
                    echo '<div class="centerflex">';
                    echo '<table class="tabledisp2 tableformat">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Bestelnr.</th>';
                    echo '<th>Achternaam</th>';
                    echo '<th>Besteldatum</th>';
                    echo '<th>Regel ID</th>';
                    echo '<th>Productnaam</th>';
                    echo '<th>Aantal</th>';
                    echo '<th>Verwijder</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    while ($aRow = $oStmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td>' . $aRow['purchase_id'] . '</td>';
                        echo '<td>' . $aRow['achternaam'] . '</td>';
                        echo '<td>' . $aRow['besteldatum'] . '</td>';
                        echo '<td>' . $aRow['line_id'] . '</td>';
                        echo '<td>' . $aRow['productnaam'] . '</td>';
                        echo '<td>' . $aRow['aantal'] . '</td>';
                        echo '<td>';

                
                        echo '<form action="pur-crud-delete.php" method="post" style="display:inline;">';
                        echo '<input type="hidden" name="line_id" value="' . $aRow['line_id'] . '">';
                        echo '<input type="hidden" name="purchase_id" value="' . $aRow['purchase_id'] . '">';
                        echo '<input type="submit" value="Regel" name="delete_type" style="margin-right:5px;">';
                        echo '</form>';

                        
                        echo '<form action="pur-crud-delete.php" method="post" style="display:inline;">';
                        echo '<input type="hidden" name="purchase_id" value="' . $aRow['purchase_id'] . '">';
                        echo '<input type="submit" value="Aankoop" name="delete_type">';
                        echo '</form>';

                        echo '</td>';
                        echo '</tr>';
                    }

                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                } else {
                    echo '<p>Er zijn geen openstaande bestellingen.</p>';
                }
            } catch (PDOException $e) {
                $sMsg = '<p>Regelnummer: ' . $e->getLine() . '<br />Bestand: ' . $e->getFile() . '<br />Foutmelding: ' . $e->getMessage() . '</p>';
                trigger_error($sMsg);
            }
            $db = null;
        ?>
    </main>

</body>
</html>
