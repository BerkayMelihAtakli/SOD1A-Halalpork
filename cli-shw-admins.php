<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
render_header('Overzicht beheerders');
require_admin();
?>
<main class="centering">
    <h2>Overzicht alle beheerders</h2>
    <?php
    $sql = "SELECT c.id, c.first_name, c.last_name, c.email, c.city, co.name AS country_name
            FROM client c
            LEFT JOIN country co ON c.country = co.idcountry
            WHERE c.isadmin = 'J'
            ORDER BY c.last_name, c.first_name";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <p>Totaal: <?php echo count($rows); ?> beheerder(s).</p>
    <table class="tabledisp2">
        <thead>
            <tr>
                <th>ID</th>
                <th>Voornaam</th>
                <th>Achternaam</th>
                <th>E-mail</th>
                <th>Woonplaats</th>
                <th>Land</th>
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
                <td><?php echo h($row['country_name']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p><a href="index.php">Terug naar home</a></p>
</main>
<?php render_footer(); ?>
