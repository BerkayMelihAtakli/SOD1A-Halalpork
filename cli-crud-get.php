<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';
render_header('Onderhoud klanten');
require_admin();
?>
<main class="centering">
    <h2>Onderhoud klanten</h2>
    <?php if (isset($_GET['msg'])): ?>
    <p><strong><?php echo h($_GET['msg']); ?></strong></p>
    <?php endif; ?>
    <table class="tabledisp2">
        <thead>
            <tr>
                <td>ID</td>
                <td>Voornaam</td>
                <td>Achternaam</td>
                <td>E-mail</td>
                <td>Woonplaats</td>
                <td>Acties</td>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT id, first_name, last_name, email, city FROM client WHERE isadmin = 'N' ORDER BY last_name, first_name";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr><form method="post">';
            echo '<td>' . h($row['id']) . '</td>';
            echo '<td>' . h($row['first_name']) . '</td>';
            echo '<td>' . h($row['last_name']) . '</td>';
            echo '<td>' . h($row['email']) . '</td>';
            echo '<td>' . h($row['city']) . '</td>';
            echo '<input type="hidden" name="client_id" value="' . h($row['id']) . '">';
            echo '<td>';
            echo '<button type="submit" formaction="cli-crud-upd.php">Wijzigen</button> ';
            echo '<button type="submit" formaction="cli-crud-del.php">Verwijderen</button>';
            echo '</td>';
            echo '</form></tr>';
        }
        ?>
        </tbody>
    </table>
</main>
<?php render_footer(); ?>
