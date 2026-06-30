<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

// Alleen bereikbaar via POST vanuit inlog-client.php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: inlog-client.php');
    exit();
}

$fout      = 'Combinatie van email-adres en wachtwoord is niet correct';
$email     = trim($_POST['email']     ?? '');
$wachtwoord = $_POST['wachtwoord'] ?? '';

// 1) E-mailadres moet geldig formaat hebben
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: index.php?msg=' . urlencode($fout));
    exit();
}

// 2) E-mailadres moet precies één keer voorkomen in de database
$stmt = $db->prepare('SELECT id, first_name, last_name, pswrd FROM client WHERE email = :email');
$stmt->bindValue(':email', $email);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($rows) !== 1) {
    header('Location: index.php?msg=' . urlencode($fout));
    exit();
}

$client = $rows[0];

// 3) Wachtwoord verifiëren met de opgeslagen hash
if (!password_verify($wachtwoord, $client['pswrd'])) {
    header('Location: index.php?msg=' . urlencode($fout));
    exit();
}

// Inloggen gelukt: sla gegevens op in de afgesproken sessievariabelen
session_regenerate_id(true);
$_SESSION['benJeErAl']       = true;
$_SESSION['welkNummerIsDit'] = (int)$client['id'];
$_SESSION['wieBenJeDan']     = trim($client['first_name'] . ' ' . $client['last_name']);
$_SESSION['SoortToegang']    = 'Klant';

// Welkomstbericht en terug naar homepage
$naam = $_SESSION['wieBenJeDan'];
header('Location: index.php?msg=' . urlencode('Welkom ' . $naam . ', inloggen is gelukt'));
exit();
?>
