<!DOCTYPE html>
<html lang="nl"> 
<head>
	 <meta charset="UTF-8">
	 <title>Bestelling plaatsen</title>
	 <link rel="stylesheet" type="text/css" href="company.css">  
</head>

<body>
    <?php
        session_start();

       
        if (!isset($_SESSION["benJeErAl"]) || $_SESSION["SoortToegang"] !== "Klant") {
            header("Refresh: 4, url=index.php");
            echo "<h2>Je moet ingelogd zijn als klant om te bestellen!</h2>";
            exit();
        }

        echo "<header class='spacebelowabove'>";
        echo "<h1>Bestelling plaatsen</h1>";
        include "nav.html";
        echo "</header>";
    ?>

    <main class="centering">
        <h2 class="spacebelowabove">LET OP: je kan maar één product tegelijk bestellen</h2>

        <?php
            require_once "dbconnect.php";
            try {
                $sQuery = "SELECT product.ID, product.productname, category.name, product.price 
                           FROM product 
                           JOIN category ON product.categoryid = category.ID 
                           WHERE product.isactive = 'J'";
                $oStmt = $db->prepare($sQuery);
                $oStmt->execute();

                if ($oStmt->rowCount() > 0) {
                    echo '<div class="centerflex">';
                    echo '<table class="tabledisp2 tableformat">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>ID</th>';
                    echo '<th>Productnaam</th>';
                    echo '<th>Categorie</th>';
                    echo '<th>Prijs</th>';
                    echo '<th>Aantal</th>';
                    echo '<th>Actie</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    while ($aRow = $oStmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td>' . $aRow['ID'] . '</td>';
                        echo '<td>' . $aRow['productname'] . '</td>';
                        echo '<td>' . $aRow['name'] . '</td>';
                        echo '<td>&euro; ' . number_format($aRow['price'], 2, ',', '.') . '</td>';
                        echo '<td>';
                        echo '<form action="pur-crud-adding.php" method="post">';
                        echo '<input type="hidden" name="productid" value="' . $aRow['ID'] . '">';
                        echo '<input type="hidden" name="price" value="' . $aRow['price'] . '">';
                        echo '<input type="number" name="quantity" value="1" min="1" style="width:60px;">';
                        echo '</td>';
                        echo '<td>';
                        echo '<input type="submit" value="Bestellen" name="submt-bestellen">';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }

                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                } else {
                    echo '<p>Er zijn geen actieve producten beschikbaar.</p>';
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
