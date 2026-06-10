<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) {
    header('Location: cat-crud-get.php');
    exit;
}

// Controleer of categorie aan een product gekoppeld is
// Pas de tabelnaam/kolomnaam aan naar jouw project
$stmt = $pdo->prepare("SELECT COUNT(*) FROM product WHERE category_id = ?");
$stmt->execute([$id]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['message'] = 'Kan niet verwijderen: categorie is gekoppeld aan een of meer producten.';
    header('Location: cat-crud-get.php');
    exit;
}

// Haal categoriegegevens op
$stmt = $pdo->prepare("SELECT * FROM category WHERE id = ?");
$stmt->execute([$id]);
$cat = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cat) {
    header('Location: cat-crud-get.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head><meta charset="UTF-8"><title>Category verwijderen</title></head>
<body>
<h1>Category verwijderen</h1>

<p>Weet je zeker dat je de volgende categorie wilt verwijderen?</p>

<table border="1" cellpadding="8">
    <tr><th>ID</th><td><?= htmlspecialchars($cat['id']) ?></td></tr>
    <tr><th>Naam</th><td><?= htmlspecialchars($cat['name']) ?></td></tr>
</table>

<br>
<form action="cat-crud-delete.php" method="POST">
    <input type="hidden" name="id" value="<?= $cat['id'] ?>">
    <a href="cat-crud-get.php"><button type="button">Breek af</button></a>
    <button type="submit" style="color:red;">Verwijder</button>
</form>
</body>
</html>
