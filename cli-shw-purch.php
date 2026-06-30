<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
render_header('Klanten met aankopen');
require_admin();
?>
<main class="centering">
    <h2>Overzicht klanten met aankopen</h2>
    <?php
    $sql = "SELECT c.id, c.first_name, c.last_name, c.email,
                   COUNT(DISTINCT pu.ID) AS aantal_bestellingen,
                   SUM(pl.quantity * pl.price) AS totaalbedrag
            FROM client c
            INNER JOIN purchase pu ON pu.clientid = c.id
            INNER JOIN purchaseline pl ON pl.purchaseid = pu.ID
            WHERE c.id > 0
            GROUP BY c.id, c.first_name, c.last_name, c.email
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
                <th>Aantal bestellingen</th>
                <th>Totaalbedrag</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?php echo h($row['id']); ?></td>
                <td><?php echo h($row['first_name']); ?></td>
                <td><?php echo h($row['last_name']); ?></td>
                <td><?php echo h($row['email']); ?></td>
                <td><?php echo h($row['aantal_bestellingen']); ?></td>
                <td>&euro; <?php echo number_format((float)$row['totaalbedrag'], 2, ',', '.'); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p><a href="index.php">Terug naar home</a></p>
</main>
<?php render_footer(); ?>
