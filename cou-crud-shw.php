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
$stmt = $pdo->query("SELECT * FROM country ORDER BY name");
$countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head><meta charset="UTF-8"><title>Overzicht Landen</title></head>
<body>
<h1>Overzicht Landen</h1>
<table border="1" cellpadding="8">
    <tr><th>ID</th><th>Naam</th><th>Code</th></tr>
    <?php foreach ($countries as $cou): ?>
    <tr>
        <td><?= htmlspecialchars($cou['idcountry']) ?></td>
        <td><?= htmlspecialchars($cou['name']) ?></td>
        <td><?= htmlspecialchars($cou['code']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<br><a href="cou-crud-get.php">Terug naar beheer</a>
</body>
</html>
