<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
render_header('Onderhoud producten');
require_admin();
?>
<main class="centering">
    <h2>Onderhoud producten</h2>
    <?php
    if (isset($_GET['msg'])) {
        echo '<p><strong>' . h($_GET['msg']) . '</strong></p>';
    }
    ?>
    <form action="pro-crud-add.php" method="post">
        <input type="submit" name="submt-sel-prod-add" value="Product toevoegen">
    </form>
    <p>&nbsp;</p>
    <p><a href="pro-crud-shw01.php">Bekijk inactieve producten</a> | <a href="pro-crud-shw02.php">Bekijk alle producten</a> | <a href="pro-active-get.php">Producten actief/inactief zetten</a></p>
    <table class="tabledisp2">
        <thead><tr><td>ID</td><td>Product</td><td>Prijs</td><td>Categorie</td><td>Leverancier</td><td>Actief</td><td>Acties</td></tr></thead>
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
            echo '<tr><form method="post">';
            echo '<td><input type="number" readonly name="product_id" value="' . h($row['ID']) . '"></td>';
            echo '<td>' . h($row['productname']) . '</td>';
            echo '<td>€ ' . h(price_to_form($row['price'])) . '</td>';
            echo '<td>' . h($row['categoryname']) . '</td>';
            echo '<td>' . h($row['company']) . '</td>';
            echo '<td>' . h($row['isactive']) . '</td>';
            echo '<td><button type="submit" formaction="pro-crud-upd.php">Wijzigen</button> <button type="submit" formaction="pro-crud-del.php">Verwijderen</button> <button type="submit" formaction="pro-active-confirm.php">Actief/Inactief</button></td>';
            echo '</form></tr>';
        }
        ?>
        </tbody>
    </table>
</main>
<?php render_footer(); ?>
