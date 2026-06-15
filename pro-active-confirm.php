<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

render_header('Product status wijzigen');
require_admin();

$id = (int)($_POST['product_id'] ?? $_GET['id'] ?? 0);

if ($id <= 0) {
    echo '<main><p>Geen product gekozen.</p><p><a href="pro-active-get.php">Terug</a></p></main>';
    render_footer();
    exit();
}

$stmt = $db->prepare("SELECT p.ID, p.productname, p.price, p.isactive, c.name AS categoryname, s.company
                      FROM product p
                      INNER JOIN category c ON p.categoryid = c.ID
                      INNER JOIN supplier s ON p.supplierid = s.ID
                      WHERE p.ID = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo '<main><p>Product niet gevonden.</p><p><a href="pro-active-get.php">Terug</a></p></main>';
    render_footer();
    exit();
}

$_SESSION['active_product_id'] = $id;

$actie = ($product['isactive'] === 'J') ? 'deactiveren' : 'activeren';
?>
<main class="centering">
    <h2>Product <?php echo h($actie); ?></h2>
    <p>Weet je zeker dat je dit product wilt <?php echo h($actie); ?>?</p>

    <table class="tabledisp2">
        <tr><td>ID</td><td><?php echo h($product['ID']); ?></td></tr>
        <tr><td>Product</td><td><?php echo h($product['productname']); ?></td></tr>
        <tr><td>Prijs</td><td>€ <?php echo h(price_to_form($product['price'])); ?></td></tr>
        <tr><td>Categorie</td><td><?php echo h($product['categoryname']); ?></td></tr>
        <tr><td>Leverancier</td><td><?php echo h($product['company']); ?></td></tr>
        <tr><td>Huidige status</td><td><?php echo h(active_text($product['isactive'])); ?></td></tr>
    </table>

    <form action="pro-active-update.php" method="post">
        <input type="hidden" name="product_id" value="<?php echo h($product['ID']); ?>">
        <button type="submit" formaction="pro-active-get.php">Breek af</button>
        <input type="submit" value="<?php echo h(ucfirst($actie)); ?>">
    </form>
</main>
<?php render_footer(); ?>
