<?php
session_start();
require_once 'dbconnect.php';
require_once 'category_country_helpers.php';
render_header('Onderhoud countries');
require_admin();
?>
<main class="centering">
    <h2>Onderhoud countries</h2>
    <?php if (isset($_GET['msg'])) { echo '<p><strong>' . h($_GET['msg']) . '</strong></p>'; } ?>
    <form action="cou-crud-add.php" method="post"><input type="submit" value="Country toevoegen"></form>
    <p>&nbsp;</p>
    <table class="tabledisp2">
        <thead><tr><td>ID</td><td>Naam</td><td>Code</td><td>Acties</td></tr></thead>
        <tbody>
        <?php
        $stmt = $db->query('SELECT idcountry, name, code FROM country ORDER BY idcountry');
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr><form method="post">';
            echo '<td><input type="number" readonly name="country_id" value="' . h($row['idcountry']) . '"></td>';
            echo '<td>' . h($row['name']) . '</td>';
            echo '<td>' . h($row['code']) . '</td>';
            echo '<td><button type="submit" formaction="cou-crud-upd.php">Wijzigen</button> <button type="submit" formaction="cou-crud-del.php">Verwijderen</button></td>';
            echo '</form></tr>';
        }
        ?>
        </tbody>
    </table>
</main>
<?php render_footer(); ?>
