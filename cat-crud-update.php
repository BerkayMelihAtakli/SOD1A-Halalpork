<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$id   = (int)($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');

if ($id === 0) {
    header('Location: cat-crud-get.php');
    exit;
}

// Validatie
if ($name === '' || !preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $name)) {
    $_SESSION['error'] = 'Naam is verplicht en mag alleen letters en spaties bevatten.';
    $_SESSION['old']   = ['name' => $name];
    header("Location: cat-crud-upd.php?id=$id");
    exit;
}

// Controleer op duplicate (uitgezonderd huidige record)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM category WHERE LOWER(name) = LOWER(?) AND id != ?");
$stmt->execute([$name, $id]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['error'] = 'Deze categorienaam bestaat al.';
    $_SESSION['old']   = ['name' => $name];
    header("Location: cat-crud-upd.php?id=$id");
    exit;
}

// Update
$stmt = $pdo->prepare("UPDATE category SET name = ? WHERE id = ?");
$stmt->execute([$name, $id]);

$_SESSION['message'] = "Categorie succesvol bijgewerkt.";
header('Location: cat-crud-get.php');
exit;
