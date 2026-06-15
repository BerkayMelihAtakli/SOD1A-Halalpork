<?php
session_start();
require_once 'dbconnect.php';
require_once 'category_country_helpers.php';
render_header('Onderhoud categorieën');
require_admin();
?>
<main class="centering">
    <h2>Onderhoud categorieën</h2>
    <?php if (isset($_GET['msg'])) { echo '<p><strong>' . h($_GET['msg']) . '</strong></p>'; } ?>
    <form action="cat-crud-add.php" method="post"><input type="submit" value="Category toevoegen"></form>
    <p>&nbsp;</p>
    <table class="tabledisp2">
        <thead><tr><td>ID</td><td>Naam</td><td>Acties</td></tr></thead>
        <tbody>
        <?php
        $stmt = $db->query('SELECT ID, name FROM category ORDER BY ID');
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr><form method="post">';
            echo '<td><input type="number" readonly name="category_id" value="' . h($row['ID']) . '"></td>';
            echo '<td>' . h($row['name']) . '</td>';
            echo '<td><button type="submit" formaction="cat-crud-upd.php">Wijzigen</button> <button type="submit" formaction="cat-crud-del.php">Verwijderen</button></td>';
            echo '</form></tr>';
        }
        ?>
        </tbody>
    </table>
</main>
<?php render_footer(); ?>
