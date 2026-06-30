<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

// Alleen beheerders mogen het onderhoud van klanten uitvoeren
render_header('Onderhoud klanten');
require_admin();
?>
<main class="centering">
    <h2>Onderhoud klanten</h2>

    <?php if (isset($_GET['msg'])): ?>
        <p><strong><?= h($_GET['msg']) ?></strong></p>
    <?php endif; ?>

    <p><a href="cli-crud-add.php"><button type="button">Nieuwe klant toevoegen</button></a></p>

    <table class="tabledisp2">
        <thead>
            <tr>
                <td>ID</td>
                <td>Voornaam</td>
                <td>Achternaam</td>
                <td>E-mail</td>
                <td>Woonplaats</td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        <?php
        // Haal alle klanten op
        $sql  = "SELECT id, first_name, last_name, email, city FROM client ORDER BY last_name, first_name";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . h($row['id'])         . '</td>';
            echo '<td>' . h($row['first_name']) . '</td>';
            echo '<td>' . h($row['last_name'])  . '</td>';
            echo '<td>' . h($row['email'])      . '</td>';
            echo '<td>' . h($row['city'])       . '</td>';
            // Knop wijzigen
            echo '<td>';
            echo '<form action="cli-crud-upd.php" method="post">';
            echo '<input type="hidden" name="client_id" value="' . h($row['id']) . '">';
            echo '<input type="submit" value="Wijzigen">';
            echo '</form>';
            echo '</td>';
            // Knop verwijderen
            echo '<td>';
            echo '<form action="cli-crud-del.php" method="post">';
            echo '<input type="hidden" name="client_id" value="' . h($row['id']) . '">';
            echo '<input type="submit" value="Verwijderen">';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</main>
<?php render_footer(); ?>
