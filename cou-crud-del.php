<?php
session_start();
require_once 'dbconnect.php';
require_once 'category_country_helpers.php';
render_header('Country verwijderen');
require_admin();
$id = (int)($_POST['country_id'] ?? $_GET['id'] ?? 0);
$country = get_country_by_id($db, $id);
?>
<main class="centering">
<?php
if (!$country) {
    echo '<h2>Country niet gevonden</h2><p><a href="cou-crud-get.php">Terug</a></p>';
} elseif (country_is_used_by_product($db, $id)) {
    echo '<h2>Verwijderen kan niet</h2><p>Dit land hoort nog bij één of meer producten via leveranciers.</p><p><a href="cou-crud-get.php">Terug</a></p>';
} else {
?>
    <h2>Country verwijderen</h2>
    <p>Weet je zeker dat je dit land wilt verwijderen?</p>
    <table class="tabledisp2"><tr><td>ID</td><td><?php echo h($country['idcountry']); ?></td></tr><tr><td>Naam</td><td><?php echo h($country['name']); ?></td></tr><tr><td>Code</td><td><?php echo h($country['code']); ?></td></tr></table>
    <form action="cou-crud-delete.php" method="post">
        <input type="hidden" name="country_id" value="<?php echo h($country['idcountry']); ?>">
        <button type="submit">Verwijder</button>
        <button type="submit" formaction="cou-crud-get.php" formnovalidate>Breek af</button>
    </form>
<?php } ?>
</main>
<?php render_footer(); ?>
