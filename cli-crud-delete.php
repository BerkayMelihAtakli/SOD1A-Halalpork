<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';
require_admin();

$id = (int)($_POST['client_id'] ?? 0);
if (!isset($_SESSION['delete_client_id']) || (int)$_SESSION['delete_client_id'] !== $id) {
    header('Location: cli-crud-get.php?msg=' . urlencode('Ongeldige verwijdering: klant-ID klopt niet.'));
    exit();
}

// Re-check for open orders before deleting
$check = $db->prepare(
    "SELECT COUNT(*) FROM purchase WHERE clientid = :id AND delivered = 0"
);
$check->execute([':id' => $id]);
if ((int)$check->fetchColumn() > 0) {
    header('Location: cli-crud-get.php?msg=' . urlencode('Klant kan niet verwijderd worden: openstaande bestellingen gevonden.'));
    exit();
}

$stmt = $db->prepare('DELETE FROM client WHERE id = :id');
$stmt->execute([':id' => $id]);
unset($_SESSION['delete_client_id']);

header('Location: cli-crud-get.php?msg=' . urlencode('Klant succesvol verwijderd.'));
exit();
?>
