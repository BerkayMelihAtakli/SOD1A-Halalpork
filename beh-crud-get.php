<?php
session_start();
require_once 'dbconnect.php';

// Alleen voor ingelogde beheerders
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}


$eigenId   = (int)($_SESSION['id'] ?? 0);
$eigenNaam = $_SESSION['name'] ?? 'Onbekend';
$eigenRol  = $_SESSION['role'];


$stmt = $pdo->prepare(
    "SELECT id, first_name, last_name, email, isadmin
     FROM client
     WHERE isadmin = 'J' AND id != ?
     ORDER BY last_name, first_name"
);
$stmt->execute([$eigenId]);
$beheerders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="nl">
<head><meta charset="UTF-8"><title>Beheerrechten verwijderen</title></head>
<body>
<h1>Beheerrechten verwijderen</h1>

<p>Ingelogd als: <strong><?= htmlspecialchars($eigenNaam) ?></strong>
   (autorisatie: <strong><?= htmlspecialchars($eigenRol) ?></strong>)</p>

<?php if ($message): ?>
    <p style="color:green;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<h2>Overige beheerders</h2>

<?php if (count($beheerders) === 0): ?>
    <p>Er zijn geen andere gebruikers met beheerrechten.</p>
<?php else: ?>
<!-- Rubriek "Overzicht in tabelvorm op het scherm" -->
<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Naam</th>
        <th>E-mail</th>
        <th>Autorisatie</th>
        <th>Acties</th>
    </tr>
    <?php foreach ($beheerders as $b): ?>
    <tr>
        <td><?= htmlspecialchars($b['id']) ?></td>
        <td><?= htmlspecialchars($b['first_name'] . ' ' . $b['last_name']) ?></td>
        <td><?= htmlspecialchars($b['email']) ?></td>
        <td><?= $b['isadmin'] === 'J' ? 'Beheerder' : 'Klant' ?></td>
        <td>
            <a href="beh-crud-del.php?id=<?= $b['id'] ?>"><button>Beheerrechten verwijderen</button></a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>

<br><a href="index.php">Terug naar hoofdmenu</a>
</body>
</html>