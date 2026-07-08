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

$error = $_SESSION['error'] ?? '';
$old   = $_SESSION['old'] ?? [];
unset($_SESSION['error'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="nl">
<head><meta charset="UTF-8"><title>Country toevoegen</title></head>
<body>
<h1>Country toevoegen</h1>

<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form action="cou-crud-adding.php" method="POST">
    <label>Naam: 
        <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
    </label>
    <br><br>
    <label>Code: 
        <input type="text" name="code" value="<?= htmlspecialchars($old['code'] ?? '') ?>" required>
    </label>
    <br><br>
    <a href="cou-crud-get.php"><button type="button">Breek af</button></a>
    <button type="submit">Sla op</button>
</form>
</body>
</html>
