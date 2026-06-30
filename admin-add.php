<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

render_header('Beheerrechten toekennen');
require_admin();

$stmt = $db->prepare(
    "SELECT id, first_name, last_name, email, city
     FROM client
     WHERE isadmin = 'N' AND id <> 0
     ORDER BY last_name, first_name"
);
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<main class="centering">
    <h2>Beheerrechten toekennen</h2>

    <?php if (empty($clients)): ?>
        <p>Er zijn geen klanten zonder beheerrechten gevonden.</p>
    <?php else: ?>
    <table class="tabledisp2">
        <thead>
            <tr>
                <th>ID</th>
                <th>Voornaam</th>
                <th>Achternaam</th>
                <th>E-mail</th>
                <th>Woonplaats</th>
                <th>Actie</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($clients as $client): ?>
            <tr>
                <td><?php echo h($client['id']); ?></td>
                <td><?php echo h($client['first_name']); ?></td>
                <td><?php echo h($client['last_name']); ?></td>
                <td><?php echo h($client['email']); ?></td>
                <td><?php echo h($client['city']); ?></td>
                <td>
                    <form action="admin-add01.php" method="post">
                        <input type="hidden" name="client_id" value="<?php echo h($client['id']); ?>">
                        <input type="submit" value="Maak beheerder">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <p><a href="index.php">Terug naar home</a></p>
</main>
<?php render_footer(); ?>
