<?php
session_start();
require_once 'dbconnect.php';

if (
    !isset($_SESSION['benJeErAl']) ||
    $_SESSION['benJeErAl'] !== true ||
    !isset($_SESSION['SoortToegang']) ||
    $_SESSION['SoortToegang'] !== 'Beheer'
) {
    header('Location: login.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id === 0) {
    header('Location: cou-crud-get.php');
    exit;
}

$stmt = $pdo->prepare("DELETE FROM country WHERE id = ?");
$stmt->execute([$id]);

$_SESSION['message'] = "Land succesvol verwijderd.";
header('Location: cou-crud-get.php');
exit;
