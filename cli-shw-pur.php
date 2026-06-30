<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
require_admin();
render_header('Klanten met aankopen');
?>
<main class="centering">
    <h2>Alle klanten met hun aankopen</h2>
    <?php
    $sql = "SELECT c.id AS client_id, c.first_name, c.last_name, c.city,
                   pu.ID AS purchase_id, pu.purchasedate, pu.delivered
            FROM client c
            INNER JOIN purchase pu ON pu.clientid = c.id
            WHERE c.id > 0
            ORDER BY c.last_name, c.first_name, pu.purchasedate";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <table class="tabledisp2">
        <thead>
            <tr>
                <th>Client ID</th>
                <th>Voornaam</th>
                <th>Achternaam</th>
                <th>Stad</th>
                <th>Aankoop ID</th>
                <th>Aankoopdatum</th>
                <th>Geleverd</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= h($row['client_id']) ?></td>
                <td><?= h($row['first_name']) ?></td>
                <td><?= h($row['last_name']) ?></td>
                <td><?= h($row['city']) ?></td>
                <td><?= h($row['purchase_id']) ?></td>
                <td><?= h($row['purchasedate']) ?></td>
                <td><?= h($row['delivered']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p><a href="index.php">Terug naar home</a></p>
</main>
<?php render_footer(); ?>
