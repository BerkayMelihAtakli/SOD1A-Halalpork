<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
render_header('Product verwijderen');
require_admin();

$id = (int)($_POST['product_id'] ?? $_GET['id'] ?? 0);
if ($id <= 0) {
    echo '<main><p>Geen product gekozen.</p><p><a href="pro-crud-get.php">Terug</a></p></main>';
    render_footer();
    exit();
}

$check = $db->prepare("SELECT COUNT(*) FROM purchaseline pl INNER JOIN purchase pu ON pl.purchaseid = pu.ID WHERE pl.productid = :id AND pu.delivered = 0");
$check->execute([':id' => $id]);
if ((int)$check->fetchColumn() > 0) {
    echo '<main><h2>Product kan niet verwijderd worden</h2><p>Dit product hoort nog bij een niet-afgeleverde bestelling.</p><p><a href="pro-crud-get.php">Terug naar onderhoud producten</a></p></main>';
    render_footer();
    exit();
}

$stmt = $db->prepare("SELECT p.*, c.name AS categoryname, s.company
                      FROM product p
                      INNER JOIN category c ON p.categoryid = c.ID
                      INNER JOIN supplier s ON p.supplierid = s.ID
                      WHERE p.ID = :id");
$stmt->execute([':id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    echo '<main><p>Product niet gevonden.</p><p><a href="pro-crud-get.php">Terug</a></p></main>';
    render_footer();
    exit();
}
$_SESSION['delete_product_id'] = $id;
?>
<main class="centering">
    <h2>Product verwijderen</h2>
    <p>Weet je zeker dat je dit product wilt verwijderen?</p>
    <table class="tabledisp2">
        <tr><td>ID</td><td><?php echo h($product['ID']); ?></td></tr>
        <tr><td>Product</td><td><?php echo h($product['productname']); ?></td></tr>
        <tr><td>Ingrediënten</td><td><?php echo h($product['ingredients']); ?></td></tr>
        <tr><td>Allergenen</td><td><?php echo h($product['allergens']); ?></td></tr>
        <tr><td>Prijs</td><td>€ <?php echo h(price_to_form($product['price'])); ?></td></tr>
        <tr><td>Categorie</td><td><?php echo h($product['categoryname']); ?></td></tr>
        <tr><td>Leverancier</td><td><?php echo h($product['company']); ?></td></tr>
        <tr><td>Actief</td><td><?php echo h($product['isactive']); ?></td></tr>
    </table>
    <form action="pro-crud-delete.php" method="post">
        <input type="hidden" name="product_id" value="<?php echo h($product['ID']); ?>">
        <button type="submit" formaction="pro-crud-get.php">Breek af</button>
        <input type="submit" name="product_delete" value="Verwijder">
    </form>
</main>
<?php render_footer(); ?>
