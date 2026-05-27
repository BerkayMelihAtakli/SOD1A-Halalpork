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

        // Controleer of klant is ingelogd
        if (!isset($_SESSION["benJeErAl"]) || $_SESSION["SoortToegang"] !== "Klant") {
            header("Refresh: 4, url=index.php");
            echo "<h2>Je moet ingelogd zijn als klant om je bestellingen te bekijken!</h2>";
            exit();
        }

        echo "<header class='spacebelowabove'>";
        echo "<h1>Mijn bestellingen</h1>";
        include "nav.html";
        echo "</header>";
    ?>

    <main class="centering">
        <h2 class="spacebelowabove">Overzicht van mijn bestellingen</h2>

        <?php
            require_once "dbconnect.php";
            $clientid = $_SESSION["welkNummerIsDit"];

            try {
                $sQuery = "SELECT purchase.ID AS bestelnummer,
                                  purchase.purchasedate AS besteldatum,
                                  purchase.delivered AS afgeleverd,
                                  product.productname AS productnaam,
                                  purchaseline.price AS prijs,
                                  purchaseline.quantity AS aantal
                           FROM purchase
                           JOIN purchaseline ON purchaseline.purchaseid = purchase.ID
                           JOIN product ON product.ID = purchaseline.productid
                           WHERE purchase.clientid = :clientid
                           ORDER BY purchase.ID DESC";

                $oStmt = $db->prepare($sQuery);
                $oStmt->bindValue(":clientid", $clientid);
                $oStmt->execute();

                if ($oStmt->rowCount() > 0) {
                    echo '<div class="centerflex">';
                    echo '<table class="tabledisp2 tableformat">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Bestelnummer</th>';
                    echo '<th>Besteldatum</th>';
                    echo '<th>Afgeleverd</th>';
                    echo '<th>Productnaam</th>';
                    echo '<th>Prijs</th>';
                    echo '<th>Aantal</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    while ($aRow = $oStmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td>' . $aRow['bestelnummer'] . '</td>';
                        echo '<td>' . $aRow['besteldatum'] . '</td>';
                        echo '<td>' . ($aRow['afgeleverd'] ? 'Ja' : 'Nee') . '</td>';
                        echo '<td>' . $aRow['productnaam'] . '</td>';
                        echo '<td>&euro; ' . number_format($aRow['prijs'], 2, ',', '.') . '</td>';
                        echo '<td>' . $aRow['aantal'] . '</td>';
                        echo '</tr>';
                    }

                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                } else {
                    echo '<p>Je hebt nog geen bestellingen geplaatst.</p>';
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
