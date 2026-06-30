<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

// Alleen beheerders mogen beheerrechten toekennen
render_header('Beheerrechten toekennen');
require_admin();
?>
<main class="centering">
    <h2>Beheerrechten toekennen</h2>
    <p>Kies een klant om beheerrechten toe te kennen.</p>
    <table class="tabledisp2">
        <thead>
            <tr>
                <td>ID</td>
                <td>Voornaam</td>
                <td>Achternaam</td>
                <td>E-mail</td>
                <td>Woonplaats</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        <?php
        // Stap 1: toon alle klanten zonder beheerrechten met id <> 0
        $sql  = "SELECT id, first_name, last_name, email, city
                 FROM client
                 WHERE isadmin = 'N' AND id <> 0
                 ORDER BY last_name, first_name";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . h($row['id'])         . '</td>';
            echo '<td>' . h($row['first_name']) . '</td>';
            echo '<td>' . h($row['last_name'])  . '</td>';
            echo '<td>' . h($row['email'])      . '</td>';
            echo '<td>' . h($row['city'])       . '</td>';
            echo '<td>';
            // Knop "Maak beheerder" per klant als apart formulier
            echo '<form action="admin-add01.php" method="post">';
            echo '<input type="hidden" name="client_id" value="' . h($row['id']) . '">';
            echo '<input type="submit" value="Maak beheerder">';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</main>
<?php render_footer(); ?>
