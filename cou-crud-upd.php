<?php
session_start();
require_once 'dbconnect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) {
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

$error = $_SESSION['error'] ?? '';
$old   = $_SESSION['old'] ?? $cou;
unset($_SESSION['error'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="nl">
<head><meta charset="UTF-8"><title>Country wijzigen</title></head>
<body>
<h1>Country wijzigen</h1>

<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form action="cou-crud-update.php" method="POST">
    <input type="hidden" name="id" value="<?= $cou['id'] ?>">

    <label>ID: <input type="text" value="<?= htmlspecialchars($cou['id']) ?>" disabled></label>
    <br><br>
    <label>Naam: 
        <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? $cou['name']) ?>" required>
    </label>
    <br><br>
    <label>Code: 
        <input type="text" name="code" value="<?= htmlspecialchars($old['code'] ?? $cou['code']) ?>" required>
    </label>
    <br><br>
    <a href="cou-crud-get.php"><button type="button">Breek af</button></a>
    <button type="submit">Opslaan</button>
</form>
</body>
</html>
