<?php
require_once "dbconnect.php";
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Alle categorieën met hun producten</title>
    <style>
        body{
            font-family: Arial, sans-serif;
        }

        table{
            border-collapse: collapse;
            width: 80%;
        }

        th, td{
            border:1px solid black;
            padding:8px;
        }

        th{
            background:#dddddd;
        }
    </style>
</head>
<body>

<h1>Alle categorieën met hun producten</h1>

<table>

<tr>
    <th>Category ID</th>
    <th>Categorie</th>
    <th>Product</th>
</tr>

<?php

$sql = "
SELECT
    category.ID,
    category.name,
    product.productname
FROM category
INNER JOIN product
ON product.categoryID = category.ID
WHERE product.active = 1
ORDER BY category.name, product.productname
";

$stmt = $conn->prepare($sql);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
?>

<tr>
    <td><?= $row["ID"] ?></td>
    <td><?= htmlspecialchars($row["name"]) ?></td>
    <td><?= htmlspecialchars($row["productname"]) ?></td>
</tr>

<?php
}
?>

</table>

</body>
</html>