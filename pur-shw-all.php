<?php
session_start();
require_once "dbconnect.php";

$stmt = $db->query("
    SELECT purchase.ID, client.first_name, client.last_name, client.city,
           purchase.purchasedate, purchase.delivered
    FROM purchase
    LEFT JOIN client ON purchase.clientid = client.id
    ORDER BY purchase.ID
");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Alle aankopen</title>
    <link rel="stylesheet" type="text/css" href="company.css">
</head>
<body>

<?php include "nav.html"; ?>

<h2>Alle aankopen</h2>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>purchase.ID</th>
            <th>client.first_name</th>
            <th>client.last_name</th>
            <th>client.city</th>
            <th>purchasedate</th>
            <th>delivered</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= (int)$row['ID'] ?></td>
            <td><?= htmlspecialchars($row['first_name'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['last_name'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['city'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['purchasedate'] ?? '') ?></td>
            <td><?= $row['delivered'] !== null ? (int)$row['delivered'] : '' ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
