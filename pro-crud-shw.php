<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

render_header('Producten');
?>
<main class="centering">
    <h2>Ons assortiment</h2>
    <table class="tabledisp2">
        <thead>
            <tr>
                <td>Productnaam</td>
                <td>Categorie</td>
                <td>Prijs</td>
                <td>Allergenen</td>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT p.productname, p.price, p.allergens, c.name AS category
                FROM product p
                LEFT JOIN category c ON p.categoryid = c.ID
                WHERE p.isactive = 'J'
                ORDER BY c.name, p.productname";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
        ?>
            <tr>
                <td><?= h($row['productname']) ?></td>
                <td><?= h($row['category'])    ?></td>
                <td>€<?= number_format((float)$row['price'], 2, ',', '') ?></td>
                <td><?= h($row['allergens'])   ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</main>
<?php render_footer(); ?>
