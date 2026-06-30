<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';
render_header('Onderhoud klanten');
require_admin();
?>
<main class="centering">
    <h2>Onderhoud klanten</h2>
    <?php
    if (isset($_GET['msg'])) {
        echo '<p><strong>' . h($_GET['msg']) . '</strong></p>';
    }
    ?>
    <form action="cli-crud-add.php" method="post">
        <input type="submit" name="submt-sel-cli-add" value="Klant toevoegen">
    </form>
    <p>&nbsp;</p>
    <table class="tabledisp2">
        <thead>
            <tr>
                <td>ID</td>
                <td>Voornaam</td>
                <td>Achternaam</td>
                <td>E-mail</td>
                <td>Woonplaats</td>
                <td>Land</td>
                <td>Telefoonnummer</td>
                <td>Acties</td>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT c.id, c.first_name, c.last_name, c.email, c.city, co.name AS country_name, c.telephone
                FROM client c
                LEFT JOIN country co ON c.country = co.idcountry
                ORDER BY c.last_name, c.first_name";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr><form method="post">';
            echo '<td><input type="number" readonly name="client_id" value="' . h($row['id']) . '"></td>';
            echo '<td>' . h($row['first_name']) . '</td>';
            echo '<td>' . h($row['last_name']) . '</td>';
            echo '<td>' . h($row['email']) . '</td>';
            echo '<td>' . h($row['city']) . '</td>';
            echo '<td>' . h($row['country_name']) . '</td>';
            echo '<td>' . h($row['telephone']) . '</td>';
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
