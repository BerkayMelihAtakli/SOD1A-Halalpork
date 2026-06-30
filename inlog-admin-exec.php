<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: inlog-admin.php');
    exit();
}

$token = $_POST['csrf_token'] ?? '';
if (empty($token) || !hash_equals($_SESSION['csrf_inlog_admin'] ?? '', $token)) {
    header('Location: inlog-admin.php?msg=' . urlencode('Ongeldige toegang.'));
    exit();
}

$fout       = 'Combinatie van email-adres en wachtwoord is niet correct';
$email      = trim($_POST['email'] ?? '');
$wachtwoord = $_POST['wachtwoord'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: inlog-admin.php?msg=' . urlencode($fout));
    exit();
}

try {
    $stmt = $db->prepare(
        "SELECT id, first_name, last_name, isadmin, pswrd FROM client WHERE email = ?"
    );
    $stmt->execute([$email]);
    $rijen = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($rijen) !== 1) {
        header('Location: inlog-admin.php?msg=' . urlencode($fout));
        exit();
    }

    $client = $rijen[0];

    if ($client['isadmin'] !== 'J') {
        header('Location: inlog-admin.php?msg=' . urlencode($fout));
        exit();
    }

    if (!password_verify($wachtwoord, $client['pswrd'])) {
        header('Location: inlog-admin.php?msg=' . urlencode($fout));
        exit();
    }

    if (password_needs_rehash($client['pswrd'], PASSWORD_DEFAULT)) {
        $nieuwHash = password_hash($wachtwoord, PASSWORD_DEFAULT);
        $db->prepare("UPDATE client SET pswrd = ? WHERE id = ?")->execute([$nieuwHash, $client['id']]);

        if (!password_verify($wachtwoord, $nieuwHash)) {
            header('Location: inlog-admin.php?msg=' . urlencode($fout));
            exit();
        }
    }

    session_regenerate_id(true);

    $_SESSION['benJeErAl']       = true;
    $_SESSION['welkNummerIsDit'] = (int)$client['id'];
    $_SESSION['wieBenJeDan']     = trim($client['first_name'] . ' ' . $client['last_name']);
    $_SESSION['SoortToegang']    = 'Beheer';

    unset($_SESSION['csrf_inlog_admin']);

    ?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welkom — The Bread Company</title>
    <link rel="stylesheet" href="company.css">
</head>
<body class="hp-page">
<?php include 'nav.html'; ?>
<div class="ok-wrap">
    <div class="ok-card">
        <div class="ok-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7d5ba6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h1>Welkom, <?= h($_SESSION['wieBenJeDan']) ?>!</h1>
        <p>Je bent ingelogd als beheerder. Je hebt nu toegang tot alle beheerfuncties.</p>
        <a href="index.php" class="ok-btn">Naar home</a>
    </div>
</div>
</body>
</html>
    <?php

} catch (PDOException $e) {
    header('Location: inlog-admin.php?msg=' . urlencode('Er is een technische fout opgetreden.'));
    exit();
}
?>
