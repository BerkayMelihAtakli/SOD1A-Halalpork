<?php
// Stap 3: UPDATE uitvoeren om beheerrechten toe te kennen
// Alleen toegankelijk via SESSION van stap 2 (admin-add01.php)
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

// Toegangscontrole: SESSION moet gezet zijn door stap 2
if (!isset($_SESSION['admin_grant_id'])) {
    header('Location: admin-add.php');
    exit();
}

render_header('Beheerrechten toegekend');
require_admin();

// Klant-ID ophalen uit SESSION (nooit uit POST/GET, want niet te vertrouwen)
$id = (int)$_SESSION['admin_grant_id'];

// Dubbele controle: bestaat de klant nog EN heeft deze nog geen beheerrechten?
$check = $db->prepare(
    "SELECT id, first_name, last_name
     FROM client
     WHERE id = :id AND isadmin = 'N'"
);
$check->bindValue(':id', $id, PDO::PARAM_INT);
$check->execute();
$client = $check->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    // Klant bestaat niet meer of is al beheerder geworden: geen actie
    unset($_SESSION['admin_grant_id']);
    echo '<main><p>Klant bestaat niet meer of heeft al beheerrechten. Geen wijziging uitgevoerd.</p>';
    echo '<p><a href="admin-add.php">Terug naar overzicht</a></p></main>';
    render_footer();
    exit();
}

// UPDATE: stel isadmin in op 'J' voor deze klant
$stmt = $db->prepare("UPDATE client SET isadmin = 'J' WHERE id = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

// SESSION opruimen na succesvolle UPDATE
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
