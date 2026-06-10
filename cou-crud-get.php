<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM country ORDER BY name");
$countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="nl">
<head><meta charset="UTF-8"><title>Onderhoud countries</title></head>
<body>
<h1>Onderhoud countries</h1>

<?php if ($message): ?>
    <p style="color:green;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<a href="cou-crud-add.php"><button>Country toevoegen</button></a>
<br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Naam</th>
        <th>Code</th>
        <th>Acties</th>
    </tr>
    <?php foreach ($countries as $cou): ?>
    <tr>
        <td><?= htmlspecialchars($cou['id']) ?></td>
        <td><?= htmlspecialchars($cou['name']) ?></td>
        <td><?= htmlspecialchars($cou['code']) ?></td>
        <td>
            <a href="cou-crud-upd.php?id=<?= $cou['id'] ?>"><button>Wijzigen</button></a>
            <a href="cou-crud-del.php?id=<?= $cou['id'] ?>"><button>Verwijderen</button></a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
