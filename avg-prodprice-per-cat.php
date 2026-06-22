<?php
require_once "dbconnect.php";

$stmt = $db->query("
    SELECT c.ID AS Categorie, c.name AS CategorieOms, AVG(p.price) AS gem_prijs
    FROM category c
    LEFT JOIN product p ON p.categoryid = c.ID
    GROUP BY c.ID, c.name
    ORDER BY c.ID
");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Gemiddelde prijs per categorie</title>
</head>
<body>

<h2>Overzicht categorieën met gemiddelde prijs</h2>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>Categorie</th>
            <th>CategorieOms</th>
            <th>gem_prijs</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= (int)$row['Categorie'] ?></td>
            <td><?= htmlspecialchars($row['CategorieOms']) ?></td>
            <td>
                <?= $row['gem_prijs'] !== null
                    ? '&euro; ' . number_format((float)$row['gem_prijs'], 2, ',', '.')
                    : '&mdash;' ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
