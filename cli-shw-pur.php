<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

// Alleen beheerders mogen dit overzicht bekijken
render_header('Alle klanten met hun aankopen');
require_admin();
?>
<main class="centering">
    <h2>Alle klanten met hun aankopen</h2>
    <table class="tabledisp2">
        <thead>
            <tr>
                <td>Client ID</td>
                <td>Voornaam</td>
                <td>Achternaam</td>
                <td>Woonplaats</td>
                <td>Aankoop ID</td>
                <td>Aankoopdatum</td>
                <td>Afgeleverd</td>
            </tr>
        </thead>
        <tbody>
        <?php
        // INNER JOIN: alleen klanten die minimaal één aankoop hebben gedaan
        $sql = "SELECT c.ID AS client_id, c.first_name, c.last_name, c.city,
                       p.id AS purchase_id, p.purchasedate, p.delivered
                FROM client c
                INNER JOIN purchase p ON p.clientid = c.ID
                ORDER BY c.last_name, c.first_name, p.purchasedate";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . h($row['client_id'])    . '</td>';
            echo '<td>' . h($row['first_name'])   . '</td>';
            echo '<td>' . h($row['last_name'])    . '</td>';
            echo '<td>' . h($row['city'])         . '</td>';
            echo '<td>' . h($row['purchase_id'])  . '</td>';
            echo '<td>' . h($row['purchasedate']) . '</td>';
            echo '<td>' . h($row['delivered'])    . '</td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</main>
<?php render_footer(); ?>
