<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
render_header('Product wijzigen');
require_admin();

$id = (int)($_POST['product_id'] ?? $_GET['id'] ?? 0);
if ($id <= 0) {
    echo '<main><p>Geen product gekozen.</p><p><a href="pro-crud-get.php">Terug</a></p></main>';
    render_footer();
    exit();
}
$_SESSION['update_product_id'] = $id;
$stmt = $db->prepare('SELECT * FROM product WHERE ID = :id');
$stmt->execute([':id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    echo '<main><p>Product niet gevonden.</p><p><a href="pro-crud-get.php">Terug</a></p></main>';
    render_footer();
    exit();
}
?>
<main class="centering">
    <h2>Product wijzigen</h2>
    <?php
    if (isset($_SESSION['product_errors'])) {
        echo '<ul>';
        foreach ($_SESSION['product_errors'] as $error) {
            echo '<li>' . h($error) . '</li>';
        }
        echo '</ul>';
        unset($_SESSION['product_errors']);
        if (isset($_SESSION['old_product'])) {
            $product = array_merge($product, $_SESSION['old_product']);
            unset($_SESSION['old_product']);
        }
    }
    ?>
    <form action="pro-crud-update.php" method="post" class="tabledisp">
        <label>ID</label><input type="number" name="product_id" value="<?php echo h($product['ID']); ?>" readonly>
        <label>Actief</label><input type="text" name="isactive" value="<?php echo h($product['isactive']); ?>" readonly>
        <?php product_form_fields($db, $product); ?>
        <p>
            <button type="submit" formaction="pro-crud-get.php">Breek af</button>
            <input type="submit" name="product_update" value="Opslaan">
        </p>
    </form>
</main>
<?php render_footer(); ?>
