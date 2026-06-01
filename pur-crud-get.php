<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

render_header('Onderhoud aankopen');
require_admin();
?>
<main class="centering">
    <h2>Onderhoud aankopen</h2>
    <?php
    try {
        $sQuery = "SELECT pu.ID AS purchase_id, pu.purchasedate, pu.delivered, c.id AS client_id, c.first_name, c.last_name, pl.ID AS purchaseline_id, pr.productname, pl.price, pl.quantity
                   FROM purchase pu
                   JOIN client c ON pu.clientid = c.id
                   JOIN purchaseline pl ON pl.purchaseid = pu.ID
                   JOIN product pr ON pl.productid = pr.ID
                   ORDER BY pu.purchasedate DESC, pu.ID, pl.ID";
        $oStmt = $db->prepare($sQuery);
        $oStmt->execute();

        if ($oStmt->rowCount() > 0) {
            echo '<table class="tabledisp"><thead><tr>';
            echo '<th>Bestelnummer</th><th>Klant</th><th>Besteldatum</th><th>Afgeleverd</th><th>Regelnummer</th><th>Product</th><th>Prijs</th><th>Aantal</th>';
            echo '</tr></thead><tbody>';
            while ($aRow = $oStmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . h($aRow['purchase_id']) . '</td>';
                echo '<td>' . h($aRow['first_name'] . ' ' . $aRow['last_name']) . '</td>';
                echo '<td>' . h($aRow['purchasedate']) . '</td>';
                echo '<td>' . ($aRow['delivered'] ? 'Ja' : 'Nee') . '</td>';
                echo '<td>' . h($aRow['purchaseline_id']) . '</td>';
                echo '<td>' . h($aRow['productname']) . '</td>';
                echo '<td>&euro; ' . number_format($aRow['price'], 2, ',', '.') . '</td>';
                echo '<td>' . h($aRow['quantity']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<p>Er zijn nog geen aankopen gevonden.</p>';
        }
    } catch (PDOException $e) {
        $sMsg = '<p>Regelnummer: '.$e->getLine().'<br />Bestand: '.$e->getFile().'<br />Foutmelding: '.$e->getMessage().'</p>';
        trigger_error($sMsg);
    }
    ?>
</main>
<?php
render_footer();
?>