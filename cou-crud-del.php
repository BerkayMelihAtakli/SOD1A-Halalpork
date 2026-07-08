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

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) {
    header('Location: cou-crud-get.php');
    exit;
}


$stmt = $pdo->prepare("SELECT COUNT(*) FROM product WHERE country_id = ?");
$stmt->execute([$id]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['message'] = 'Kan niet verwijderen: land is gekoppeld aan een of meer producten.';
    header('Location: cou-crud-get.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM country WHERE id = ?");
$stmt->execute([$id]);
$cou = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cou) {
    header('Location: cou-crud-get.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head><meta charset="UTF-8"><title>Country verwijderen</title></head>
<body>
<h1>Country verwijderen</h1>

<p>Weet je zeker dat je het volgende land wilt verwijderen?</p>

<table border="1" cellpadding="8">
    <tr><th>ID</th><td><?= htmlspecialchars($cou['id']) ?></td></tr>
    <tr><th>Naam</th><td><?= htmlspecialchars($cou['name']) ?></td></tr>
    <tr><th>Code</th><td><?= htmlspecialchars($cou['code']) ?></td></tr>
</table>

<br>
<form action="cou-crud-delete.php" method="POST">
    <input type="hidden" name="id" value="<?= $cou['id'] ?>">
    <a href="cou-crud-get.php"><button type="button">Breek af</button></a>
    <button type="submit" style="color:red;">Verwijder</button>
</form>
</body>
</html>
