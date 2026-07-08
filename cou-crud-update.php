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
$id   = (int)($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$code = trim($_POST['code'] ?? '');

if ($id === 0) {
    header('Location: cou-crud-get.php');
    exit;
}


if ($name === '' || !preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $name)) {
    $_SESSION['error'] = 'Naam is verplicht en mag alleen letters en spaties bevatten.';
    $_SESSION['old']   = compact('name', 'code');
    header("Location: cou-crud-upd.php?id=$id");
    exit;
}


if ($code === '' || !preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $code)) {
    $_SESSION['error'] = 'Code is verplicht en mag alleen letters en spaties bevatten.';
    $_SESSION['old']   = compact('name', 'code');
    header("Location: cou-crud-upd.php?id=$id");
    exit;
}


$stmt = $pdo->prepare("SELECT COUNT(*) FROM country WHERE LOWER(name) = LOWER(?) AND id != ?");
$stmt->execute([$name, $id]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['error'] = 'Deze landnaam bestaat al.';
    $_SESSION['old']   = compact('name', 'code');
    header("Location: cou-crud-upd.php?id=$id");
    exit;
}


$stmt = $pdo->prepare("SELECT COUNT(*) FROM country WHERE LOWER(code) = LOWER(?) AND id != ?");
$stmt->execute([$code, $id]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['error'] = 'Deze landcode bestaat al.';
    $_SESSION['old']   = compact('name', 'code');
    header("Location: cou-crud-upd.php?id=$id");
    exit;
}


$stmt = $pdo->prepare("UPDATE country SET name = ?, code = ? WHERE id = ?");
$stmt->execute([$name, $code, $id]);

$_SESSION['message'] = "Land succesvol bijgewerkt.";
header('Location: cou-crud-get.php');
exit;
