<?php
session_start();
require_once 'dbconnect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$eigenId = (int)($_SESSION['id'] ?? 0);
$id      = (int)($_POST['id'] ?? 0);

if ($id === 0) {
    header('Location: beh-crud-get.php');
    exit;
}

if ($id === $eigenId) {
    $_SESSION['message'] = 'Je kunt je eigen beheerrechten niet verwijderen.';
    header('Location: beh-crud-get.php');
    exit;
}

$stmt = $pdo->prepare("SELECT first_name, last_name, isadmin FROM client WHERE id = ?");
$stmt->execute([$id]);
$beheerder = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$beheerder || $beheerder['isadmin'] !== 'J') {
    $_SESSION['message'] = 'Deze gebruiker heeft geen beheerrechten (meer).';
    header('Location: beh-crud-get.php');
    exit;
}

$stmt = $pdo->query("SELECT COUNT(*) FROM client WHERE isadmin = 'J'");
if ((int)$stmt->fetchColumn() <= 1) {
    $_SESSION['message'] = 'Kan niet verwijderen: dit is de laatste beheerder van het systeem.';
    header('Location: beh-crud-get.php');
    exit;
}


$stmt = $pdo->prepare("UPDATE client SET isadmin = 'N' WHERE id = ?");
$stmt->execute([$id]);


$naam = trim($beheerder['first_name'] . ' ' . $beheerder['last_name']);
$_SESSION['message'] = "Beheerrechten van '$naam' zijn succesvol verwijderd.";
header('Location: beh-crud-get.php');
exit;
