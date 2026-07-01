<?php
session_start();
require_once 'dbconnect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$eigenId = (int)($_SESSION['id'] ?? 0);
$id      = (int)($_GET['id'] ?? 0);

if ($id === 0) {
    header('Location: beh-crud-get.php');
    exit;
}

// Bescherming: je eigen beheerrechten kun je hier niet verwijderen
if ($id === $eigenId) {
    $_SESSION['message'] = 'Je kunt je eigen beheerrechten niet op deze manier verwijderen.';
    header('Location: beh-crud-get.php');
    exit;
}

// Gegevens van de doel-beheerder ophalen
$stmt = $pdo->prepare("SELECT id, first_name, last_name, email, isadmin FROM client WHERE id = ?");
$stmt->execute([$id]);
$beheerder = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$beheerder || $beheerder['isadmin'] !== 'J') {
    $_SESSION['message'] = 'Deze gebruiker heeft geen beheerrechten (meer).';
    header('Location: beh-crud-get.php');
    exit;
}


$stmt = $pdo->query("SELECT COUNT(*) FROM client WHERE isadmin = 'J'");
if ((int)$stmt->fetchColumn() <= 1) {
    $_SESSION['message'] = 'Kan niet verwijderen: dit is de laatste beheerder van het systeem.';
    header('Location: beh-crud-get.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head><meta charset="UTF-8"><title>Beheerrechten verwijderen</title></head>
<body>
<h1>Beheerrechten verwijderen</h1>


<p>Weet je zeker dat je de beheerrechten van onderstaande gebruiker wilt verwijderen?</p>

<table border="1" cellpadding="8">
    <tr><th>ID</th><td><?= htmlspecialchars($beheerder['id']) ?></td></tr>
    <tr><th>Naam</th><td><?= htmlspecialchars($beheerder['first_name'] . ' ' . $beheerder['last_name']) ?></td></tr>
    <tr><th>E-mail</th><td><?= htmlspecialchars($beheerder['email']) ?></td></tr>
    <tr><th>Autorisatie</th><td>Beheerder</td></tr>
</table>


<br>
<form action="beh-crud-delete.php" method="POST">
    <input type="hidden" name="id" value="<?= $beheerder['id'] ?>">
    <a href="beh-crud-get.php"><button type="button">Nee, breek af</button></a>
    <button type="submit" style="color:red;">Ja, verwijder beheerrechten</button>
</form>
</body>
</html>
