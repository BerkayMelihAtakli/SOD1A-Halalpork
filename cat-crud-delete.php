<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id === 0) {
    header('Location: cat-crud-get.php');
    exit;
}

$stmt = $pdo->prepare("DELETE FROM category WHERE id = ?");
$stmt->execute([$id]);

$_SESSION['message'] = "Categorie succesvol verwijderd.";
header('Location: cat-crud-get.php');
exit;
