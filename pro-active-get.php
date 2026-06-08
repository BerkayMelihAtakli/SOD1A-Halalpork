<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

render_header('Producten actief/inactief zetten');
require_admin();
?>
<main class="centering">
    <h2>Producten actief/inactief zetten</h2>

    <?php
    if (isset($_GET['msg'])) {
        echo '<p><strong>' . h($_GET['msg']) . '</strong></p>';
    }
    ?>

    <table class="tabledisp2">
        <thead>
            <tr>
                <td>ID</td>
                <td>Product</td>
                <td>Prijs</td>
                <td>Categorie</td>
                <td>Leverancier</td>
                <td>Status</td>
                <td>Actie</td>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT p.ID, p.productname, p.price, p.isactive, c.name AS categoryname, s.company
                FROM product p
                INNER JOIN category c ON p.categoryid = c.ID
                INNER JOIN supplier s ON p.supplierid = s.ID
                ORDER BY p.ID";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . h($row['ID']) . '</td>';
            echo '<td>' . h($row['productname']) . '</td>';
            echo '<td>€ ' . h(price_to_form($row['price'])) . '</td>';
            echo '<td>' . h($row['categoryname']) . '</td>';
            echo '<td>' . h($row['company']) . '</td>';
            echo '<td>' . h(active_text($row['isactive'])) . '</td>';
            echo '<td>';
            echo '<form action="pro-active-confirm.php" method="post">';
            echo '<input type="hidden" name="product_id" value="' . h($row['ID']) . '">';
            if ($row['isactive'] === 'J') {
                echo '<input type="submit" value="Deactiveren">';
            } else {
                echo '<input type="submit" value="Activeren">';
            }
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>

    <p><a href="pro-crud-get.php">Terug naar onderhoud producten</a></p>
</main>
<?php render_footer(); ?>
