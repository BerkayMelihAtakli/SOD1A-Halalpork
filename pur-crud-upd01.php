<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bevestig wijziging bestelling</title>
    <link rel="stylesheet" type="text/css" href="company.css">
</head>
<body>
<?php
session_start();
require_once "dbconnect.php";

if (!isset($_SESSION["benJeErAl"]) || $_SESSION["benJeErAl"] !== true || $_SESSION["SoortToegang"] !== "Klant") {
    echo "<h2>Alleen voor ingelogde klanten</h2>";
    exit();
}

if (!isset($_POST['save_pl'])) {
    echo "<p>Geen wijziging ontvangen.</p><p><a href='pur-crud-upd.php'>Terug</a></p>";
    exit();
}

$plID = intval($_POST['save_pl']);
$newQuantity = isset($_POST['quantity'][$plID]) ? intval($_POST['quantity'][$plID]) : 0;

if ($newQuantity < 1) {
    echo "<p>Hoeveelheid moet minimaal 1 zijn.</p><p><a href='pur-crud-upd.php'>Terug</a></p>";
    exit();
}

try {
    $sQ = "SELECT pl.quantity, pr.productname FROM purchaseline pl JOIN product pr ON pl.productid = pr.ID WHERE pl.ID = :plID";
    $oS = $db->prepare($sQ);
    $oS->bindValue(':plID', $plID);
    $oS->execute();
    if ($oS->rowCount() != 1) {
        echo "<p>Purchaseline niet gevonden.</p><p><a href='pur-crud-upd.php'>Terug</a></p>";
        exit();
    }
    $a = $oS->fetch(PDO::FETCH_ASSOC);

    echo "<header class='spacebelowabove'><h1>Controleer wijziging</h1>";
    include "nav.html";
    echo "</header>";

    echo "<main class='centering'>";
    echo "<p>Product: " . htmlspecialchars($a['productname']) . "</p>";
    echo "<p>Huidig aantal: " . htmlspecialchars($a['quantity']) . "</p>";
    echo "<p>Nieuw aantal: " . htmlspecialchars($newQuantity) . "</p>";

    echo "<form action='pur-crud-update.php' method='post'>";
    echo "<input type='hidden' name='plID' value='".htmlspecialchars($plID)."'>";
    echo "<input type='hidden' name='quantity' value='".htmlspecialchars($newQuantity)."'>";
    echo "<button type='submit' name='confirm' value='1'>Bevestigen</button> ";
    echo "<button type='submit' formaction='pur-crud-upd.php'>Afbreken</button>";
    echo "</form>";

    echo "</main>";

} catch (PDOException $e) {
    $sMsg = '<p>Regelnummer: '.$e->getLine().'<br />Bestand: '.$e->getFile().'<br />Foutmelding: '.$e->getMessage().'</p>';
    trigger_error($sMsg);
}

?>
</body>
</html>
