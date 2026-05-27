<?php
session_start();
require_once "dbconnect.php";

// Alleen bereikbaar vanaf pur-crud-add.php
if (!isset($_POST['submt-pur-add']) || !isset($_SESSION['chk_pur_add'])) {
    echo "<h2>Niet op de juiste manier gekomen.</h2>";
    echo "<p><a href='pur-crud-add.php'>Terug naar bestellen</a></p>";
    exit();
}

if (!isset($_SESSION["benJeErAl"]) || $_SESSION["benJeErAl"] !== true || $_SESSION["SoortToegang"] !== "Klant") {
    echo "<h2>Alleen voor ingelogde klanten</h2>";
    exit();
}

$productid = intval($_POST['productid'] ?? 0);
$price = floatval($_POST['price'] ?? 0);
$quantity = intval($_POST['quantity'] ?? 0);

if ($productid <= 0 || $quantity < 1) {
    echo "<h2>Ongeldige productgegevens</h2>";
    echo "<p><a href='pur-crud-add.php'>Terug</a></p>";
    exit();
}

try {
    // Stap 1: Purchase record aanmaken als nog niet in SESSION
    if (!isset($_SESSION['purchase_id']) || empty($_SESSION['purchase_id'])) {
        $sIns = "INSERT INTO purchase (clientid, purchasedate, delivered) VALUES (:clientid, :purchasedate, 0)";
        $oIns = $db->prepare($sIns);
        $oIns->bindValue(':clientid', $_SESSION['welkNummerIsDit']);
        $oIns->bindValue(':purchasedate', date('Y-m-d'));
        $oIns->execute();
        $lastId = $db->lastInsertId();
        $_SESSION['purchase_id'] = $lastId;
    }

    // Stap 2: Purchaseline aanmaken
    $sPL = "INSERT INTO purchaseline (purchaseid, productid, price, quantity) VALUES (:purchaseid, :productid, :price, :quantity)";
    $oPL = $db->prepare($sPL);
    $oPL->bindValue(':purchaseid', $_SESSION['purchase_id']);
    $oPL->bindValue(':productid', $productid);
    $oPL->bindValue(':price', $price);
    $oPL->bindValue(':quantity', $quantity);
    $oPL->execute();

    echo "<h2>Bestelling is opgeslagen</h2>";
    echo "<p>Je kan een nieuw product aan de bestelling toevoegen.</p>";
    echo "<p><a href='pur-crud-add.php'>Terug naar producten</a></p>";

} catch (PDOException $e) {
    $sMsg = '<p>Regelnummer: '.$e->getLine().'<br />Bestand: '.$e->getFile().'<br />Foutmelding: '.$e->getMessage().'</p>';
    trigger_error($sMsg);
}

// laat de klant terugkeren
header('Refresh: 3; url=pur-crud-add.php');
exit();

?>
