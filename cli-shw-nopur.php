<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

// Alleen beheerders mogen dit overzicht bekijken
render_header('Alle klanten zonder aankopen');
require_admin();
?>
<main class="centering">
    <h2>Alle klanten zonder aankopen</h2>
    <table class="tabledisp2">
        <thead>
            <tr>
                <td>Client ID</td>
                <td>Voornaam</td>
                <td>Achternaam</td>
                <td>Woonplaats</td>
            </tr>
        </thead>
        <tbody>
        <?php
        // WHERE: client.id mag NIET voorkomen in het DISTINCT overzicht van clientid's uit purchase
        $sql = "SELECT c.ID, c.first_name, c.last_name, c.city
                FROM client c
                WHERE c.ID NOT IN (SELECT DISTINCT clientid FROM purchase)
                ORDER BY c.last_name, c.first_name";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . h($row['ID'])         . '</td>';
            echo '<td>' . h($row['first_name']) . '</td>';
            echo '<td>' . h($row['last_name'])  . '</td>';
            echo '<td>' . h($row['city'])       . '</td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</main>
<?php render_footer(); ?>
