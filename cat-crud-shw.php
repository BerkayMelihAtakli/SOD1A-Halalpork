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
$stmt = $pdo->query("SELECT ID AS id, name FROM category ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head><meta charset="UTF-8"><title>Overzicht Categorieën</title></head>
<body>
<h1>Overzicht Categorieën</h1>
<table border="1" cellpadding="8">
    <tr><th>ID</th><th>Naam</th></tr>
    <?php foreach ($categories as $cat): ?>
    <tr>
        <td><?= htmlspecialchars($cat['id']) ?></td>
        <td><?= htmlspecialchars($cat['name']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<br><a href="cat-crud-get.php">Terug naar beheer</a>
</body>
</html>
