<?php
session_start();
require_once 'dbconnect.php';
require_once 'category_country_helpers.php';
render_header('Categorie verwijderen');
require_admin();
$id = (int)($_POST['category_id'] ?? $_GET['id'] ?? 0);
$category = get_category_by_id($db, $id);
?>
<main class="centering">
<?php
if (!$category) {
    echo '<h2>Categorie niet gevonden</h2><p><a href="cat-crud-get.php">Terug</a></p>';
} elseif (category_is_used($db, $id)) {
    echo '<h2>Verwijderen kan niet</h2><p>Deze categorie hoort nog bij één of meer producten.</p><p><a href="cat-crud-get.php">Terug</a></p>';
} else {
?>
    <h2>Categorie verwijderen</h2>
    <p>Weet je zeker dat je deze categorie wilt verwijderen?</p>
    <table class="tabledisp2"><tr><td>ID</td><td><?php echo h($category['ID']); ?></td></tr><tr><td>Naam</td><td><?php echo h($category['name']); ?></td></tr></table>
    <form action="cat-crud-delete.php" method="post">
        <input type="hidden" name="category_id" value="<?php echo h($category['ID']); ?>">
        <button type="submit">Verwijder</button>
        <button type="submit" formaction="cat-crud-get.php" formnovalidate>Breek af</button>
    </form>
<?php } ?>
</main>
<?php render_footer(); ?>
