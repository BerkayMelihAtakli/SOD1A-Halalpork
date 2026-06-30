<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

if (!isset($_SESSION['admin_grant_id'])) {
    header('Location: admin-add.php');
    exit();
}

render_header('Beheerrechten toegekend');
require_admin();

$id = (int)$_SESSION['admin_grant_id'];

$check = $db->prepare(
    "SELECT id, first_name, last_name
     FROM client
     WHERE id = :id AND isadmin = 'N'"
);
$check->bindValue(':id', $id, PDO::PARAM_INT);
$check->execute();
$client = $check->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    unset($_SESSION['admin_grant_id']);
    echo '<main><p>Klant bestaat niet meer of heeft al beheerrechten. Geen wijziging uitgevoerd.</p>';
    echo '<p><a href="admin-add.php">Terug naar overzicht</a></p></main>';
    render_footer();
    exit();
}

$stmt = $db->prepare("UPDATE client SET isadmin = 'J' WHERE id = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

unset($_SESSION['admin_grant_id']);
?>
<main class="centering">
    <h2>Beheerrechten toegekend</h2>
    <p>
        De beheerrechten voor
        <strong><?php echo h($client['first_name']) . ' ' . h($client['last_name']); ?></strong>
        zijn succesvol toegekend.
    </p>
    <p><a href="index.php">Terug naar home</a></p>
</main>
<?php render_footer(); ?>
