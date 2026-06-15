<?php
session_start();
require_once 'dbconnect.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    header('Location: login.php?msg=' . urlencode('Vul e-mailadres en wachtwoord in.'));
    exit();
}

$stmt = $db->prepare("SELECT id, first_name, last_name, email, isadmin, pswrd FROM client WHERE email = ? AND id <> 0 LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['pswrd'])) {
    header('Location: login.php?msg=' . urlencode('E-mailadres of wachtwoord is onjuist.'));
    exit();
}

$_SESSION['benJeErAl'] = true;
$_SESSION['welkNummerIsDit'] = (int)$user['id'];
$_SESSION['wieBenJeDan'] = trim($user['first_name'] . ' ' . $user['last_name']);

if ($user['isadmin'] === 'J') {
    $_SESSION['SoortToegang'] = 'Beheer';
    header('Location: pro-crud-get.php?msg=' . urlencode('Je bent ingelogd als beheerder.'));
} else {
    $_SESSION['SoortToegang'] = 'Klant';
    header('Location: index.php?msg=' . urlencode('Je bent ingelogd als klant.'));
}
exit();
?>
