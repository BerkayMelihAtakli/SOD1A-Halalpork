<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$code = trim($_POST['code'] ?? '');

// Validatie
if ($name === '' || !preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $name)) {
    $_SESSION['error'] = 'Naam is verplicht en mag alleen letters en spaties bevatten.';
    $_SESSION['old']   = compact('name', 'code');
    header('Location: cou-crud-add.php');
    exit;
}

if ($code === '' || !preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $code)) {
    $_SESSION['error'] = 'Code is verplicht en mag alleen letters en spaties bevatten.';
    $_SESSION['old']   = compact('name', 'code');
    header('Location: cou-crud-add.php');
    exit;
}

// Controleer duplicate naam
$stmt = $pdo->prepare("SELECT COUNT(*) FROM country WHERE LOWER(name) = LOWER(?)");
$stmt->execute([$name]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['error'] = 'Deze landnaam bestaat al.';
    $_SESSION['old']   = compact('name', 'code');
    header('Location: cou-crud-add.php');
    exit;
}

// Controleer duplicate code
$stmt = $pdo->prepare("SELECT COUNT(*) FROM country WHERE LOWER(code) = LOWER(?)");
$stmt->execute([$code]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['error'] = 'Deze landcode bestaat al.';
    $_SESSION['old']   = compact('name', 'code');
    header('Location: cou-crud-add.php');
    exit;
}

// Opslaan
$stmt = $pdo->prepare("INSERT INTO country (name, code) VALUES (?, ?)");
$stmt->execute([$name, $code]);

$_SESSION['message'] = "Land '$name' succesvol toegevoegd.";
header('Location: cou-crud-get.php');
exit;
