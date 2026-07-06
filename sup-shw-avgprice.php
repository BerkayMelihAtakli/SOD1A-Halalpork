<?php
require_once "dbconnect.php";

$connectie = mysqli_connect($dbhost, $dbgebruiker, $dbwachtwoord, $dbnaam);

if (!$connectie) {
    die("Verbinding met de database is mislukt: " . mysqli_connect_error());
}

$sql = "SELECT supplier.ID, supplier.company, supplier.adress, supplier.streetnr,
               supplier.city, country.name, AVG(product.price) AS gemprijs
        FROM supplier
        INNER JOIN country ON supplier.countryid = country.ID
        INNER JOIN product ON supplier.ID = product.supplierid
        GROUP BY supplier.ID, supplier.company, supplier.adress, supplier.streetnr,
                 supplier.city, country.name
        ORDER BY supplier.ID";

$resultaat = mysqli_query($connectie, $sql);

if (!$resultaat) {
    die("Fout in de query: " . mysqli_error($connectie));
}

function h($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
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

        <?php while ($rij = mysqli_fetch_assoc($resultaat)) : ?>
        <tr>
            <td><?= h($rij['ID']) ?></td>
            <td><?= h($rij['company']) ?></td>
            <td><?= h($rij['adress']) ?></td>
            <td><?= h($rij['streetnr']) ?></td>
            <td><?= h($rij['city']) ?></td>
            <td><?= h($rij['name']) ?></td>
            <td>&euro; <?= number_format($rij['gemprijs'], 2, ',', '.') ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php mysqli_close($connectie); ?>
