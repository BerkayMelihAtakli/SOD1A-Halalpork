<?php
session_start();
require_once 'dbconnect.php';
require_once 'category_country_helpers.php';
render_header('Overzicht categorieën');
require_admin();
?>
<main class="centering">
    <h2>Overzicht categorieën</h2>
    <p><a href="cat-crud-get.php">Naar onderhoud categorieën</a></p>
    <table class="tabledisp2">
        <thead><tr><td>ID</td><td>Naam</td></tr></thead>
        <tbody>
        <?php
        $stmt = $db->query('SELECT ID, name FROM category ORDER BY ID');
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr><td>' . h($row['ID']) . '</td><td>' . h($row['name']) . '</td></tr>';
        }
        ?>
        </tbody>
    </table>
</main>
<?php render_footer(); ?>
