<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$name = trim($_POST['name'] ?? '');

// Validatie: verplicht + alleen letters en spaties
if ($name === '' || !preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $name)) {
    $_SESSION['error'] = 'Naam is verplicht en mag alleen letters en spaties bevatten.';
    $_SESSION['old']   = ['name' => $name];
    header('Location: cat-crud-add.php');
    exit;
}

// Controleer op duplicate
$stmt = $pdo->prepare("SELECT COUNT(*) FROM category WHERE LOWER(name) = LOWER(?)");
$stmt->execute([$name]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['error'] = 'Deze categorienaam bestaat al.';
    $_SESSION['old']   = ['name' => $name];
    header('Location: cat-crud-add.php');
    exit;
}

// Opslaan
$stmt = $pdo->prepare("INSERT INTO category (name) VALUES (?)");
$stmt->execute([$name]);

$_SESSION['message'] = "Categorie '$name' succesvol toegevoegd.";
header('Location: cat-crud-get.php');
exit;
