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

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="nl">
<head><meta charset="UTF-8"><title>Onderhoud categorieën</title></head>
<body>
<h1>Onderhoud categorieën</h1>

<?php if ($message): ?>
    <p style="color:green;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<a href="cat-crud-add.php"><button>Category toevoegen</button></a>
<br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Naam</th>
        <th>Acties</th>
    </tr>
    <?php foreach ($categories as $cat): ?>
    <tr>
        <td><?= htmlspecialchars($cat['id']) ?></td>
        <td><?= htmlspecialchars($cat['name']) ?></td>
        <td>
            <a href="cat-crud-upd.php?id=<?= $cat['id'] ?>"><button>Wijzigen</button></a>
            <a href="cat-crud-del.php?id=<?= $cat['id'] ?>"><button>Verwijderen</button></a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
