<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

// Alleen beheerders mogen dit overzicht bekijken
render_header('Alle beheerders');
require_admin();
?>
<main class="centering">
    <h2>Alle beheerders</h2>
    <table class="tabledisp2">
        <thead>
            <tr>
                <td>Client ID</td>
                <td>Voornaam</td>
                <td>Achternaam</td>
                <td>E-mail</td>
                <td>Adres</td>
                <td>Postcode</td>
                <td>Woonplaats</td>
            </tr>
        </thead>
        <tbody>
        <?php
        // Haal alle klanten op waarbij isadmin = 'J'
        $sql = "SELECT ID, first_name, last_name, email, adress, zipcode, city
                FROM client
                WHERE isadmin = 'J'
                ORDER BY last_name, first_name";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . h($row['ID'])         . '</td>';
            echo '<td>' . h($row['first_name']) . '</td>';
            echo '<td>' . h($row['last_name'])  . '</td>';
            echo '<td>' . h($row['email'])      . '</td>';
            echo '<td>' . h($row['adress'])     . '</td>';
            echo '<td>' . h($row['zipcode'])    . '</td>';
            echo '<td>' . h($row['city'])       . '</td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</main>
<?php render_footer(); ?>
