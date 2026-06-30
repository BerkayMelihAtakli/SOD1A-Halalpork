<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
require_admin();
render_header('Klanten zonder aankopen');
?>
<main class="centering">
    <h2>Alle klanten zonder aankopen</h2>
    <?php
    $sql = "SELECT c.id, c.first_name, c.last_name, c.city
            FROM client c
            WHERE c.id > 0
              AND c.id NOT IN (SELECT DISTINCT clientid FROM purchase)
            ORDER BY c.last_name, c.first_name";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <table class="tabledisp2">
        <thead>
            <tr>
                <th>ID</th>
                <th>Voornaam</th>
                <th>Achternaam</th>
                <th>Stad</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= h($row['id']) ?></td>
                <td><?= h($row['first_name']) ?></td>
                <td><?= h($row['last_name']) ?></td>
                <td><?= h($row['city']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p><a href="index.php">Terug naar home</a></p>
</main>
<?php render_footer(); ?>
