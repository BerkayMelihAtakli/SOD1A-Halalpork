<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';
render_header('Klantenoverzicht');
?>
<main class="centering">
    <h2>Klantenoverzicht</h2>
    <table class="tabledisp2">
        <thead>
            <tr>
                <td>Voornaam</td>
                <td>Achternaam</td>
                <td>Woonplaats</td>
                <td>Land</td>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT c.first_name, c.last_name, c.city, co.name AS country_name
                FROM client c
                LEFT JOIN country co ON c.country = co.idcountry
                WHERE c.id > 0
                ORDER BY c.last_name, c.first_name";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . h($row['first_name']) . '</td>';
            echo '<td>' . h($row['last_name']) . '</td>';
            echo '<td>' . h($row['city']) . '</td>';
            echo '<td>' . h($row['country_name']) . '</td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</main>
<?php render_footer(); ?>
