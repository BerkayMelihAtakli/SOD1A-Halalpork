<?php
session_start();
require_once 'dbconnect.php';

try {
    $stmt = $db->prepare("SELECT id, first_name, last_name FROM client WHERE isadmin = 'J' ORDER BY id LIMIT 1");
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        $_SESSION['benJeErAl'] = true;
        $_SESSION['welkNummerIsDit'] = (int)$admin['id'];
        $_SESSION['wieBenJeDan'] = trim($admin['first_name'] . ' ' . $admin['last_name']);
        $_SESSION['SoortToegang'] = 'Beheer';
    } else {
        // Fallback als er geen admin in de database staat.
        $_SESSION['benJeErAl'] = true;
        $_SESSION['welkNummerIsDit'] = 0;
        $_SESSION['wieBenJeDan'] = 'Demo Beheerder';
        $_SESSION['SoortToegang'] = 'Beheer';
    }

    header('Location: pro-crud-get.php?msg=' . urlencode('Je bent ingelogd als beheerder.'));
    exit();
} catch (PDOException $e) {
    header('Location: login.php?msg=' . urlencode('Inloggen als beheerder is mislukt.'));
    exit();
}
?>
