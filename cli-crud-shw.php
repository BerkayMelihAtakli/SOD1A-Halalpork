<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';
render_header('Klantenoverzicht');
require_admin();
?>
<main class="centering">
    <h2>Klantenoverzicht</h2>
    <table class="tabledisp2">
        <thead>
            <tr>
                <td>Voornaam</td>
                <td>Achternaam</td>
                <td>E-mail</td>
                <td>Woonplaats</td>
                <td>Land</td>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT c.first_name, c.last_name, c.email, c.city, co.name AS country_name
                FROM client c
                LEFT JOIN country co ON c.country = co.idcountry
                WHERE c.isadmin = 'N'
                ORDER BY c.last_name, c.first_name";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . h($row['first_name']) . '</td>';
            echo '<td>' . h($row['last_name']) . '</td>';
            echo '<td>' . h($row['email']) . '</td>';
            echo '<td>' . h($row['city']) . '</td>';
            echo '<td>' . h($row['country_name'] ?? '—') . '</td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</main>
<?php render_footer(); ?>
