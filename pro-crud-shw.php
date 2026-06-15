<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
render_header('Actieve producten');
?>
<main class="centering">
    <h2>Actieve producten</h2>
    <table class="tabledisp2">
        <thead><tr><td>Product</td><td>Allergenen</td><td>Categorie</td><td>Prijs</td></tr></thead>
        <tbody>
        <?php
        $sql = "SELECT p.productname, p.allergens, c.name AS categoryname, p.price
                FROM product p
                INNER JOIN category c ON p.categoryid = c.ID
                WHERE p.isactive = 'J'
                ORDER BY p.productname";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr><td>' . h($row['productname']) . '</td><td>' . h($row['allergens']) . '</td><td>' . h($row['categoryname']) . '</td><td>€ ' . h(price_to_form($row['price'])) . '</td></tr>';
        }
        ?>
        </tbody>
    </table>
</main>
<?php render_footer(); ?>
