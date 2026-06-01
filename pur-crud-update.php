<?php
session_start();
require_once "dbconnect.php";

if (!isset($_SESSION["benJeErAl"]) || $_SESSION["benJeErAl"] !== true || !isset($_SESSION["SoortToegang"]) || $_SESSION["SoortToegang"] !== "Klant") {
    echo "<h2>Alleen voor ingelogde klanten</h2>";
    exit();
}

if (!isset($_POST['plID']) || !isset($_POST['quantity'])) {
    echo "<p>Geen gegevens ontvangen.</p><p><a href='pur-crud-upd.php'>Terug</a></p>";
    exit();
}

$plID = intval($_POST['plID']);
$quantity = intval($_POST['quantity']);
if ($quantity < 1) {
    echo "<p>Aantal moet minimaal 1 zijn.</p><p><a href='pur-crud-upd.php'>Terug</a></p>";
    exit();
}

try {
    $sUpd = "UPDATE purchaseline pl INNER JOIN purchase pu ON pl.purchaseid = pu.ID SET pl.quantity = :quantity WHERE pl.ID = :plID AND pu.clientid = :clientid AND pu.delivered = 0";
    $oUpd = $db->prepare($sUpd);
    $oUpd->bindValue(':quantity', $quantity);
    $oUpd->bindValue(':plID', $plID);
    $oUpd->bindValue(':clientid', $_SESSION['welkNummerIsDit']);
    $oUpd->execute();

    if ($oUpd->rowCount() === 0) {
        echo "<h2>Wijziging niet opgeslagen</h2>";
        echo "<p>De bestelling is mogelijk niet meer wijzigbaar of behoort niet tot u.</p>";
        echo "<p><a href='pur-crud-upd.php'>Terug naar wijzigen</a></p>";
        exit();
    }

    echo "<h2>Wijziging opgeslagen</h2>";
    echo "<p>De wijziging is doorgevoerd.</p>";
    echo "<p><a href='pur-crud-upd.php'>Terug naar wijzigen</a></p>";

} catch (PDOException $e) {
    $sMsg = '<p>Regelnummer: '.$e->getLine().'<br />Bestand: '.$e->getFile().'<br />Foutmelding: '.$e->getMessage().'</p>';
    trigger_error($sMsg);
}

?>
