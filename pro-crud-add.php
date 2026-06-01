<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
render_header('Product toevoegen');
require_admin();
?>
<main class="centering">
    <h2>Product toevoegen</h2>
    <?php
    if (isset($_SESSION['product_errors'])) {
        echo '<ul>';
        foreach ($_SESSION['product_errors'] as $error) {
            echo '<li>' . h($error) . '</li>';
        }
        echo '</ul>';
        unset($_SESSION['product_errors']);
    }
    $old = $_SESSION['old_product'] ?? [];
    unset($_SESSION['old_product']);
    ?>
    <form action="pro-crud-adding.php" method="post" class="tabledisp">
        <?php product_form_fields($db, $old); ?>
        <p>
            <button type="submit" formaction="pro-crud-get.php">Breek af</button>
            <input type="submit" name="product_add" value="Sla op">
        </p>
    </form>
</main>
<?php render_footer(); ?>
