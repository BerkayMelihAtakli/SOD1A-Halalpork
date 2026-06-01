<?php
session_start();
require_once 'dbconnect.php';
require_once 'category_country_helpers.php';
render_header('Categorie wijzigen');
require_admin();
$id = (int)($_POST['category_id'] ?? $_GET['id'] ?? 0);
$category = get_category_by_id($db, $id);
?>
<main class="centering">
<?php if (!$category) { echo '<h2>Categorie niet gevonden</h2><p><a href="cat-crud-get.php">Terug</a></p>'; } else { ?>
    <h2>Categorie wijzigen</h2>
    <?php if (isset($_GET['error'])) { echo '<p><strong>' . h($_GET['error']) . '</strong></p>'; } ?>
    <form action="cat-crud-update.php" method="post">
        <label>ID</label><input type="number" name="category_id" value="<?php echo h($category['ID']); ?>" readonly>
        <label>Naam</label><input type="text" name="name" value="<?php echo h($category['name']); ?>" required pattern="[A-Za-zÀ-ÿ ]+">
        <p><button type="submit">Opslaan</button> <button type="submit" formaction="cat-crud-get.php" formnovalidate>Breek af</button></p>
    </form>
<?php } ?>
</main>
<?php render_footer(); ?>
