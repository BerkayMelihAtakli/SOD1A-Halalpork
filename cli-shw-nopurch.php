<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
render_header('Klanten zonder aankoop');
require_admin();
?>
<main class="centering">
    <h2>Overzicht klanten zonder aankoop</h2>
    <?php
    $sql = "SELECT c.id, c.first_name, c.last_name, c.email, c.city
            FROM client c
            WHERE c.id > 0
              AND c.id NOT IN (SELECT DISTINCT clientid FROM purchase)
            ORDER BY c.last_name, c.first_name";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <p>Totaal: <?php echo count($rows); ?> klant(en) hebben nog nooit besteld.</p>
    <table class="tabledisp2">
        <thead>
            <tr>
                <th>ID</th>
                <th>Voornaam</th>
                <th>Achternaam</th>
                <th>E-mail</th>
                <th>Woonplaats</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?php echo h($row['id']); ?></td>
                <td><?php echo h($row['first_name']); ?></td>
                <td><?php echo h($row['last_name']); ?></td>
                <td><?php echo h($row['email']); ?></td>
                <td><?php echo h($row['city']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p><a href="index.php">Terug naar home</a></p>
</main>
<?php render_footer(); ?>
