<?php
require_once "dbconnect.php";

$stmt = $db->query("SELECT supplier.ID, supplier.company, supplier.adress, supplier.streetnr,
                           supplier.city, country.name, AVG(product.price) AS gemprijs
                    FROM supplier
                    INNER JOIN country ON supplier.countryid = country.ID
                    INNER JOIN product ON supplier.ID = product.supplierid
                    GROUP BY supplier.ID, supplier.company, supplier.adress, supplier.streetnr,
                             supplier.city, country.name
                    ORDER BY supplier.ID");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Leveranciers met gemiddelde prijs</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; margin: 40px; }
        h1 { color: #2c3e50; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #999999; padding: 8px 12px; text-align: left; }
        th { background-color: #2c3e50; color: #ffffff; }
        tr:nth-child(even) { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Alle leveranciers met gemiddelde prijs van hun producten</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Bedrijf</th>
            <th>Adres</th>
            <th>Huisnummer</th>
            <th>Plaats</th>
            <th>Land</th>
            <th>Gemiddelde prijs</th>
        </tr>

        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['ID'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['company'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['adress'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['streetnr'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['city'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td>&euro; <?= number_format((float)$row['gemprijs'], 2, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
