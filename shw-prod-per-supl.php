<?php
<<<<<<< Updated upstream
require_once 'database/conn.php';

$sql = "SELECT s.name AS supplier_name, p.name AS product_name
        FROM supplier s
        LEFT JOIN product p ON p.supplier_id = s.id
        ORDER BY s.name, p.name";
$stmt = $db->query($sql);
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>C03-02</title></head>
<body>
<h1>Overzicht leveranciers met hun producten</h1>
<table border="1" cellpadding="5">
<tr><th>Leverancier</th><th>Product</th></tr>
<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
<tr>
<td><?= htmlspecialchars($row['supplier_name']) ?></td>
<td><?= htmlspecialchars($row['product_name'] ?? '-') ?></td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
=======
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

render_header('C03-02 Overzicht leveranciers met hun producten');

try {
    $sql = "SELECT
                s.ID AS supplier_id,
                s.company,
                s.city,
                s.telephone,
                p.ID AS product_id,
                p.productname,
                p.price,
                p.isactive,
                c.name AS categoryname
            FROM supplier s
            LEFT JOIN product p ON p.supplierid = s.ID
            LEFT JOIN category c ON p.categoryid = c.ID
            ORDER BY s.company, p.productname";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    $suppliers = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $supplierId = (int)$row['supplier_id'];

        if (!isset($suppliers[$supplierId])) {
            $suppliers[$supplierId] = [
                'company' => $row['company'],
                'city' => $row['city'],
                'telephone' => $row['telephone'],
                'products' => []
            ];
        }

        if (!empty($row['product_id'])) {
            $suppliers[$supplierId]['products'][] = [
                'productname' => $row['productname'],
                'categoryname' => $row['categoryname'],
                'price' => $row['price'],
                'isactive' => $row['isactive']
            ];
        }
    }
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
    <h2>C03-02 Overzicht leveranciers met hun producten</h2>
    <p>
        Hieronder zie je per leverancier welke producten erbij horen. Leveranciers zonder producten
        blijven ook zichtbaar door de LEFT JOIN in de query.
    </p>

    <?php if (!empty($suppliers)) { ?>
        <table class="tabledisp2">
            <thead>
                <tr>
                    <td>Leverancier</td>
                    <td>Woonplaats</td>
                    <td>Telefoon</td>
                    <td>Aantal producten</td>
                    <td>Producten</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($suppliers as $supplier) { ?>
                    <tr>
                        <td><?= h($supplier['company']); ?></td>
                        <td><?= h($supplier['city']); ?></td>
                        <td><?= h($supplier['telephone'] ?? '-'); ?></td>
                        <td><?= count($supplier['products']); ?></td>
                        <td>
                            <?php if (count($supplier['products']) > 0) { ?>
                                <ul>
                                    <?php foreach ($supplier['products'] as $product) { ?>
                                        <li>
                                            <?= h($product['productname']); ?>
                                            (<?= h($product['categoryname'] ?? 'Geen categorie'); ?>) -
                                            € <?= h(price_to_form($product['price'])); ?> -
                                            <?= h(active_text($product['isactive'])); ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } else { ?>
                                <em>Geen producten gekoppeld</em>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>Helaas, geen leveranciers gevonden.</p>
    <?php } ?>
</main>
<?php
$db = null;
render_footer();
?>
>>>>>>> Stashed changes
