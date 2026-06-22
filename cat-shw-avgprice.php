<?php
session_start();
require_once "dbconnect.php";

$stmt = $db->query("
    SELECT category.ID, category.name, avg(product.price) as `gem-prijs`
    FROM category
    LEFT JOIN product ON category.ID = product.categoryid
    GROUP BY category.ID, category.name
    ORDER BY category.ID
");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Alle categorieën met gemiddelde prijs</title>
    <link rel="stylesheet" type="text/css" href="company.css">
</head>
<body>

<?php include "nav.html"; ?>

<h2>Alle categorieën met gemiddelde prijs</h2>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>name</th>
            <th>gem-prijs</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= (int)$row['ID'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['gem-prijs'] !== null ? '&euro; ' . number_format((float)$row['gem-prijs'], 2, ',', '.') : '&mdash;' ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
