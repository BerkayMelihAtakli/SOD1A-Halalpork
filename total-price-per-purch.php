<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

render_header('C03-03 Overzicht alle aankopen met totale prijs aankoop');

try {
    $sql = "SELECT
                pu.ID AS purchase_id,
                pu.purchasedate,
                pu.delivered,
                pu.clientid,
                c.first_name,
                c.last_name,
                c.email,
                COUNT(pl.ID) AS aantal_regels,
                COALESCE(SUM(pl.price * pl.quantity), 0) AS totale_prijs
            FROM purchase pu
            LEFT JOIN client c ON c.id = pu.clientid
            LEFT JOIN purchaseline pl ON pl.purchaseid = pu.ID
            GROUP BY
                pu.ID,
                pu.purchasedate,
                pu.delivered,
                pu.clientid,
                c.first_name,
                c.last_name,
                c.email
            ORDER BY pu.purchasedate DESC, pu.ID DESC";

    $stmt = $db->prepare($sql);
    $stmt->execute();
    $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $sMsg = '<p>
                Regelnummer: ' . $e->getLine() . '<br>
                Bestand: ' . $e->getFile() . '<br>
                Foutmelding: ' . $e->getMessage() . '
            </p>';
    trigger_error($sMsg);
}
?>
<main class="centering">
    <h2>C03-03 Overzicht alle aankopen met totale prijs aankoop</h2>
    <p>
        Hieronder zie je alle aankopen. De totale prijs wordt berekend met
        <strong>prijs per aankoopregel × aantal</strong> en daarna opgeteld per aankoop.
    </p>

    <?php if (!empty($purchases)) { ?>
        <table class="tableformat" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Aankoopnummer</th>
                    <th>Datum</th>
                    <th>Klant</th>
                    <th>E-mail</th>
                    <th>Aantal regels</th>
                    <th>Afgeleverd</th>
                    <th>Totale prijs</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($purchases as $purchase) { ?>
                    <?php
                    $clientName = trim(($purchase['first_name'] ?? '') . ' ' . ($purchase['last_name'] ?? ''));
                    if ($clientName === '') {
                        $clientName = 'Onbekende klant #' . $purchase['clientid'];
                    }
                    ?>
                    <tr>
                        <td><?= h($purchase['purchase_id']); ?></td>
                        <td><?= h($purchase['purchasedate']); ?></td>
                        <td><?= h($clientName); ?></td>
                        <td><?= h($purchase['email'] ?? '-'); ?></td>
                        <td><?= h($purchase['aantal_regels']); ?></td>
                        <td><?= ((int)$purchase['delivered'] === 1) ? 'Ja' : 'Nee'; ?></td>
                        <td>&euro; <?= h(number_format((float)$purchase['totale_prijs'], 2, ',', '.')); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>Er zijn nog geen aankopen gevonden.</p>
    <?php } ?>
</main>
<?php
$db = null;
render_footer();
?>
