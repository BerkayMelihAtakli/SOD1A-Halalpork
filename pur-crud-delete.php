<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Verwijder verwerking</title>
    <link rel="stylesheet" type="text/css" href="company.css">
</head>
<body>
<?php
session_start();
require_once "dbconnect.php";

if (!isset($_SESSION["benJeErAl"]) || $_SESSION["benJeErAl"] !== true || $_SESSION["SoortToegang"] !== "Beheer") {
    echo "<h2>Alleen voor ingelogde beheerders</h2>";
    exit();
}

if (!isset($_POST['action'])) {
    echo "<p>Geen actie ontvangen.</p><p><a href='pur-crud-del.php'>Terug</a></p>";
    exit();
}

$action = $_POST['action'];
$purchaseid = intval($_POST['purchaseid'] ?? 0);
$purchaselineid = intval($_POST['purchaselineid'] ?? 0);

try {
    if ($action === 'regel') {
        // Controleer of dit de laatste regel is
        $sCnt = "SELECT COUNT(*) AS cnt FROM purchaseline WHERE purchaseid = :purchaseid";
        $oCnt = $db->prepare($sCnt);
        $oCnt->bindValue(':purchaseid', $purchaseid);
        $oCnt->execute();
        $aCnt = $oCnt->fetch(PDO::FETCH_ASSOC);
        if ($aCnt['cnt'] == 1) {
            // Waarschuwing tonen
            echo "<h2>Laatste product bij deze aankoop</h2>";
            echo "<p>Wilt u het verwijderen afbreken of wilt u de hele aankoop verwijderen?</p>";
            echo "<form action='pur-crud-del.php' method='get'><button type='submit'>Afbreken</button></form>";
            echo "<form action='pur-crud-delete.php' method='post'>";
            echo "<input type='hidden' name='action' value='aankoop'>";
            echo "<input type='hidden' name='purchaseid' value='".htmlspecialchars($purchaseid)."'>";
            echo "<button type='submit'>Verwijder aankoop</button>";
            echo "</form>";
            exit();
        } else {
            // Verwijder alleen deze purchaseline
            $sDel = "DELETE FROM purchaseline WHERE ID = :plID";
            $oDel = $db->prepare($sDel);
            $oDel->bindValue(':plID', $purchaselineid);
            $oDel->execute();
            echo "<h2>Regel verwijderd</h2>";
            echo "<p><a href='pur-crud-del.php'>Terug</a></p>";
            exit();
        }
    }

    if ($action === 'aankoop') {
        // Verwijder alle purchaselines en de purchase
        $db->beginTransaction();
        $sDelLines = "DELETE FROM purchaseline WHERE purchaseid = :purchaseid";
        $oDelLines = $db->prepare($sDelLines);
        $oDelLines->bindValue(':purchaseid', $purchaseid);
        $oDelLines->execute();

        $sDelPurchase = "DELETE FROM purchase WHERE ID = :purchaseid";
        $oDelPurchase = $db->prepare($sDelPurchase);
        $oDelPurchase->bindValue(':purchaseid', $purchaseid);
        $oDelPurchase->execute();
        $db->commit();

        echo "<h2>Aankoop verwijderd</h2>";
        echo "<p><a href='pur-crud-del.php'>Terug</a></p>";
        exit();
    }

} catch (PDOException $e) {
    if ($db->inTransaction()) $db->rollBack();
    $sMsg = '<p>Regelnummer: '.$e->getLine().'<br />Bestand: '.$e->getFile().'<br />Foutmelding: '.$e->getMessage().'</p>';
    trigger_error($sMsg);
}

?>
</body>
</html>
