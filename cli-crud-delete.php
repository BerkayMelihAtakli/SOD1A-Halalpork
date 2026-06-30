<?php
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

// Alleen bereikbaar via POST vanuit cli-crud-del.php (knop "Verwijderen")
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['client_delete'])) {
    header('Location: cli-crud-get.php');
    exit();
}

// Alleen beheerders
require_admin();

$id = (int)($_POST['client_id'] ?? 0);

// Controleer of het ID overeenkomt met de sessiewaarde (voorkomt manipulatie)
if (!isset($_SESSION['delete_client_id']) || (int)$_SESSION['delete_client_id'] !== $id) {
    header('Location: cli-crud-get.php?msg=' . urlencode('Ongeldige verwijdering: klant-ID klopt niet.'));
    exit();
}
unset($_SESSION['delete_client_id']);

// Dubbele check: beheerder mag niet verwijderd worden
$checkAdmin = $db->prepare('SELECT isadmin FROM client WHERE id = :id');
$checkAdmin->bindValue(':id', $id, PDO::PARAM_INT);
$checkAdmin->execute();
if ($checkAdmin->fetchColumn() === 'J') {
    header('Location: cli-crud-get.php?msg=' . urlencode('Klant is beheerder en mag niet verwijderd worden.'));
    exit();
}

// Dubbele check: geen openstaande bestellingen
$check = $db->prepare('SELECT COUNT(*) FROM purchase WHERE clientid = :id AND delivered = 0');
$check->bindValue(':id', $id, PDO::PARAM_INT);
$check->execute();
if ((int)$check->fetchColumn() > 0) {
    header('Location: cli-crud-get.php?msg=' . urlencode('Klant mag niet verwijderd worden vanwege openstaande bestellingen.'));
    exit();
}

// Verwijder de klant
$stmt = $db->prepare('DELETE FROM client WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

header('Location: cli-crud-get.php?msg=' . urlencode('Klant succesvol verwijderd.'));
exit();
?>
