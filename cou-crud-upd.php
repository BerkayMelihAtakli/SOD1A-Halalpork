<?php
session_start();
require_once 'dbconnect.php';
require_once 'category_country_helpers.php';
render_header('Country wijzigen');
require_admin();
$id = (int)($_POST['country_id'] ?? $_GET['id'] ?? 0);
$country = get_country_by_id($db, $id);
?>
<main class="centering">
<?php if (!$country) { echo '<h2>Country niet gevonden</h2><p><a href="cou-crud-get.php">Terug</a></p>'; } else { ?>
    <h2>Country wijzigen</h2>
    <?php if (isset($_GET['error'])) { echo '<p><strong>' . h($_GET['error']) . '</strong></p>'; } ?>
    <form action="cou-crud-update.php" method="post">
        <label>ID</label><input type="number" name="country_id" value="<?php echo h($country['idcountry']); ?>" readonly>
        <label>Naam</label><input type="text" name="name" value="<?php echo h($country['name']); ?>" required pattern="[A-Za-zÀ-ÿ ]+">
        <label>Code</label><input type="text" name="code" value="<?php echo h($country['code']); ?>" required pattern="[A-Za-zÀ-ÿ ]+" maxlength="3">
        <p><button type="submit">Opslaan</button> <button type="submit" formaction="cou-crud-get.php" formnovalidate>Breek af</button></p>
    </form>
<?php } ?>
</main>
<?php render_footer(); ?>
