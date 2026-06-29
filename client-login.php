<?php
session_start();
require_once 'dbconnect.php';

try {
    $stmt = $db->prepare("SELECT id, first_name, last_name FROM client WHERE isadmin = 'N' AND id > 0 ORDER BY id LIMIT 1");
    $stmt->execute();
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($client) {
        $_SESSION['benJeErAl'] = true;
        $_SESSION['welkNummerIsDit'] = (int)$client['id'];
        $_SESSION['wieBenJeDan'] = trim($client['first_name'] . ' ' . $client['last_name']);
        $_SESSION['SoortToegang'] = 'Klant';
    } else {
        header('Location: login.php?msg=' . urlencode('Geen klanten gevonden in de database.'));
        exit();
    }

    header('Location: index.php');
    exit();
} catch (PDOException $e) {
    header('Location: login.php?msg=' . urlencode('Inloggen als klant is mislukt.'));
    exit();
}
?>
