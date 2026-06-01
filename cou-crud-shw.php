<?php
session_start();
require_once 'dbconnect.php';
require_once 'category_country_helpers.php';
render_header('Overzicht landen');
require_admin();
?>
<main class="centering">
    <h2>Overzicht landen</h2>
    <p><a href="cou-crud-get.php">Naar onderhoud landen</a></p>
    <table class="tabledisp2">
        <thead><tr><td>ID</td><td>Naam</td><td>Code</td></tr></thead>
        <tbody>
        <?php
        $stmt = $db->query('SELECT idcountry, name, code FROM country ORDER BY idcountry');
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr><td>' . h($row['idcountry']) . '</td><td>' . h($row['name']) . '</td><td>' . h($row['code']) . '</td></tr>';
        }
        ?>
        </tbody>
    </table>
</main>
<?php render_footer(); ?>
