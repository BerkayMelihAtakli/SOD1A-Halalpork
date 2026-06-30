<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: inlog-client.php');
    exit();
}

$token = $_POST['csrf_token'] ?? '';
if (empty($token) || !hash_equals($_SESSION['csrf_inlog_client'] ?? '', $token)) {
    header('Location: inlog-client.php?msg=' . urlencode('Ongeldige toegang.'));
    exit();
}

$fout       = 'Combinatie van e-mailadres en wachtwoord is niet correct.';
$email      = trim($_POST['email'] ?? '');
$wachtwoord = $_POST['wachtwoord'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: index.php?msg=' . urlencode($fout));
    exit();
}

try {
    $stmt = $db->prepare(
        "SELECT id, first_name, last_name, pswrd FROM client WHERE email = ? AND id > 0"
    );
    $stmt->execute([$email]);
    $rijen = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($rijen) !== 1) {
        header('Location: index.php?msg=' . urlencode($fout));
        exit();
    }

    $client = $rijen[0];

    if (!password_verify($wachtwoord, $client['pswrd'])) {
        header('Location: index.php?msg=' . urlencode($fout));
        exit();
    }

    if (password_needs_rehash($client['pswrd'], PASSWORD_DEFAULT)) {
        $nieuwHash = password_hash($wachtwoord, PASSWORD_DEFAULT);
        $db->prepare("UPDATE client SET pswrd = ? WHERE id = ?")->execute([$nieuwHash, $client['id']]);
    }

    session_regenerate_id(true);
    $_SESSION['benJeErAl']       = true;
    $_SESSION['welkNummerIsDit'] = (int)$client['id'];
    $_SESSION['wieBenJeDan']     = trim($client['first_name'] . ' ' . $client['last_name']);
    $_SESSION['SoortToegang']    = 'Klant';
    unset($_SESSION['csrf_inlog_client']);

    header('Location: index.php?msg=' . urlencode('Welkom ' . $client['first_name'] . ', inloggen is gelukt.'));
    exit();

} catch (PDOException $e) {
    header('Location: index.php?msg=' . urlencode('Er is een technische fout opgetreden.'));
    exit();
}
?>
