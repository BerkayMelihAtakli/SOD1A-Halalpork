<!DOCTYPE html>
<html lang="nl"> 
<head>
	 <meta charset="UTF-8">
	 <title>Bestelling wijzigen</title>
	 <link rel="stylesheet" type="text/css" href="company.css">  
</head>

<body>
    <?php
        session_start();

        if (!isset($_SESSION["benJeErAl"]) || $_SESSION["SoortToegang"] !== "Klant") {
            header("Refresh: 4, url=index.php");
            echo "<h2>Je moet ingelogd zijn als klant!</h2>";
            exit();
        }

        echo "<header class='spacebelowabove'>";
        echo "<h1>Bestelling wijzigen</h1>";
        include "nav.html";
        echo "</header>";
    ?>

    <main class="centering">
        <h2 class="spacebelowabove">Mijn bestellingen (niet afgeleverd)</h2>

        <?php
            require_once "dbconnect.php";
            $clientid = $_SESSION["welkNummerIsDit"];

            try {
               
                $sQuery = "SELECT purchase.ID AS purchase_id,
                                  purchase.purchasedate AS besteldatum,
                                  purchaseline.ID AS line_id,
                                  product.productname AS productnaam,
                                  purchaseline.price AS prijs,
                                  purchaseline.quantity AS aantal
                           FROM purchase
                           JOIN purchaseline ON purchaseline.purchaseid = purchase.ID
                           JOIN product ON product.ID = purchaseline.productid
                           WHERE purchase.clientid = :clientid
                             AND purchase.delivered = 0
                           ORDER BY purchase.ID DESC";

                $oStmt = $db->prepare($sQuery);
                $oStmt->bindValue(":clientid", $clientid);
                $oStmt->execute();

                if ($oStmt->rowCount() > 0) {
                    echo '<form action="pur-crud-upd01.php" method="post">';
                    echo '<div class="centerflex">';
                    echo '<table class="tabledisp2 tableformat">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Bestelnummer</th>';
                    echo '<th>Besteldatum</th>';
                    echo '<th>Productnaam</th>';
                    echo '<th>Prijs</th>';
                    echo '<th>Aantal</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    while ($aRow = $oStmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td>' . $aRow['purchase_id'] . '</td>';
                        echo '<td>' . $aRow['besteldatum'] . '</td>';
                        echo '<td>' . $aRow['productnaam'] . '</td>';
                        echo '<td>&euro; ' . number_format($aRow['prijs'], 2, ',', '.') . '</td>';
                        echo '<td>';
                        echo '<input type="hidden" name="line_ids[]" value="' . $aRow['line_id'] . '">';
                        echo '<input type="number" name="quantities[' . $aRow['line_id'] . ']" value="' . $aRow['aantal'] . '" min="1" style="width:60px;">';
                        echo '</td>';
                        echo '</tr>';
                    }

                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                    echo '<p>&nbsp;</p>';
                    echo '<input type="submit" value="Opslaan" name="submt-upd">';
                    echo '</form>';
                } else {
                    echo '<p>Je hebt geen openstaande (niet afgeleverde) bestellingen om te wijzigen.</p>';
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
