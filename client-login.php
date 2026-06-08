<?php
session_start();
require_once 'dbconnect.php';

$stmt = $db->query("SELECT id, first_name, last_name FROM client WHERE isadmin = 'N' AND id <> 0 ORDER BY id LIMIT 1");
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if ($client) {
    // Voor de demo krijgt de klant altijd dit bekende wachtwoord.
    // Daardoor kan C02-01 goed getest worden.
    $demoPassword = password_hash('halalpork123', PASSWORD_DEFAULT);
    $update = $db->prepare("UPDATE client SET pswrd = ? WHERE id = ?");
    $update->execute([$demoPassword, (int)$client['id']]);

    $_SESSION['benJeErAl'] = true;
    $_SESSION['SoortToegang'] = 'Klant';
    $_SESSION['welkNummerIsDit'] = (int)$client['id'];
    $_SESSION['wieBenJeDan'] = trim($client['first_name'] . ' ' . $client['last_name']);

    header('Location: change-password.php');
    exit();
}

header('Location: login.php?msg=' . urlencode('Geen klant gevonden.'));
exit();
?>
