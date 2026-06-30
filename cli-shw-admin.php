<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
require_admin();
render_header('Alle beheerders');
?>
<main class="centering">
    <h2>Alle beheerders</h2>
    <?php
    $sql = "SELECT c.id, c.first_name, c.last_name, c.email, c.adress, c.zipcode, c.city
            FROM client c
            WHERE c.isadmin = 'J' AND c.id > 0
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
                <th>E-mail</th>
                <th>Adres</th>
                <th>Postcode</th>
                <th>Stad</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= h($row['id']) ?></td>
                <td><?= h($row['first_name']) ?></td>
                <td><?= h($row['last_name']) ?></td>
                <td><?= h($row['email']) ?></td>
                <td><?= h($row['adress']) ?></td>
                <td><?= h($row['zipcode']) ?></td>
                <td><?= h($row['city']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p><a href="index.php">Terug naar home</a></p>
</main>
<?php render_footer(); ?>
