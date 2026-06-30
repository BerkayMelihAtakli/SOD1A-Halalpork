<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

// Stap 3: alleen bereikbaar via POST vanuit admin-add01.php (knop "Bevestigen")
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['confirm_admin'])) {
    header('Location: admin-add.php');
    exit();
}

require_admin();

// ID moet in sessie staan (gezet door admin-add01.php)
if (!isset($_SESSION['admin_add_id'])) {
    header('Location: admin-add.php');
    exit();
}

$id = (int)$_SESSION['admin_add_id'];
unset($_SESSION['admin_add_id']);

// Controleer of klant nog bestaat en nog steeds geen beheerder is
$stmt = $db->prepare('SELECT id, first_name, last_name, isadmin FROM client WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    render_header('Fout');
    echo '<main class="centering"><p>Klant niet gevonden.</p>';
    echo '<p><a href="index.php">Terug naar home</a></p></main>';
    render_footer();
    exit();
}

if ($client['isadmin'] === 'J') {
    render_header('Fout');
    echo '<main class="centering"><p>Deze klant heeft al beheerrechten.</p>';
    echo '<p><a href="index.php">Terug naar home</a></p></main>';
    render_footer();
    exit();
}

// Voer UPDATE uit: zet isadmin op 'J'
$upd = $db->prepare("UPDATE client SET isadmin = 'J' WHERE id = :id");
$upd->bindValue(':id', $id, PDO::PARAM_INT);
$upd->execute();

render_header('Beheerrechten toegekend');
?>
<main class="centering">
    <h2>Beheerrechten toegekend</h2>
    <p>
        Beheerrechten zijn toegekend aan
        <strong><?= h($client['first_name'] . ' ' . $client['last_name']) ?></strong>.
    </p>
    <p><a href="index.php"><button type="button">Terug naar home</button></a></p>
</main>
<?php render_footer(); ?>
