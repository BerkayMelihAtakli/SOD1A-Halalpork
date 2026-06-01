<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
render_header('Alle producten');
require_admin();
?>
<main class="centering">
    <h2>Alle producten</h2>
    <table class="tabledisp2">
        <thead><tr><td>ID</td><td>Product</td><td>Prijs</td><td>Categorie</td><td>Leverancier</td><td>Actief</td></tr></thead>
        <tbody>
        <?php
        $sql = "SELECT p.ID, p.productname, p.price, c.name AS categoryname, s.company, p.isactive
                FROM product p
                INNER JOIN category c ON p.categoryid = c.ID
                INNER JOIN supplier s ON p.supplierid = s.ID
                ORDER BY p.ID";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr><td>' . h($row['ID']) . '</td><td>' . h($row['productname']) . '</td><td>€ ' . h(price_to_form($row['price'])) . '</td><td>' . h($row['categoryname']) . '</td><td>' . h($row['company']) . '</td><td>' . h($row['isactive']) . '</td></tr>';
        }
        ?>
        </tbody>
    </table>
</main>
<?php render_footer(); ?>
