<?php
include "dbconnect.php";
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Alle klanten met hun aantal aankopen</title>
    <style>
        table{
            border-collapse: collapse;
            width: 100%;
        }

        th, td{
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th{
            background-color: #dddddd;
        }
    </style>
</head>
<body>

<h1>Alle klanten met hun aantal aankopen</h1>

<?php

$sql = "
SELECT
    client.ID,
    client.first_name,
    client.last_name,
    client.email,
    client.zipcode,
    client.city,
    COUNT(purchase.ID) AS aantal_aankopen
FROM client
LEFT JOIN purchase
ON client.ID = purchase.client_ID
GROUP BY
    client.ID,
    client.first_name,
    client.last_name,
    client.email,
    client.zipcode,
    client.city
ORDER BY aantal_aankopen DESC
";

$result = $db->query($sql);

echo "<table>";

echo "<tr>";
echo "<th>ID</th>";
echo "<th>Voornaam</th>";
echo "<th>Achternaam</th>";
echo "<th>Email</th>";
echo "<th>Postcode</th>";
echo "<th>Plaats</th>";
echo "<th>Aantal aankopen</th>";
echo "</tr>";

foreach ($result as $row)
{
    echo "<tr>";
    echo "<td>".$row["ID"]."</td>";
    echo "<td>".$row["first_name"]."</td>";
    echo "<td>".$row["last_name"]."</td>";
    echo "<td>".$row["email"]."</td>";
    echo "<td>".$row["zipcode"]."</td>";
    echo "<td>".$row["city"]."</td>";
    echo "<td>".$row["aantal_aankopen"]."</td>";
    echo "</tr>";
}

echo "</table>";

?>

</body>
</html>