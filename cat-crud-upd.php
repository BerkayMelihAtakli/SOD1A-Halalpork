<?php
session_start();
require_once 'dbconnect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) {
    header('Location: cat-crud-get.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM category WHERE id = ?");
$stmt->execute([$id]);
$cat = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cat) {
    header('Location: cat-crud-get.php');
    exit;
}

$error = $_SESSION['error'] ?? '';
$old   = $_SESSION['old'] ?? $cat;
unset($_SESSION['error'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="nl">
<head><meta charset="UTF-8"><title>Category wijzigen</title></head>
<body>
<h1>Category wijzigen</h1>

<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form action="cat-crud-update.php" method="POST">
    <input type="hidden" name="id" value="<?= $cat['id'] ?>">

    <label>ID: <input type="text" value="<?= htmlspecialchars($cat['id']) ?>" disabled></label>
    <br><br>
    <label>Naam: 
        <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? $cat['name']) ?>" required>
    </label>
    <br><br>
    <a href="cat-crud-get.php"><button type="button">Breek af</button></a>
    <button type="submit">Opslaan</button>
</form>
</body>
</html>
