<?php
require_once 'database/conn.php';

$sql = "SELECT s.name AS supplier_name, p.name AS product_name
        FROM supplier s
        LEFT JOIN product p ON p.supplier_id = s.id
        ORDER BY s.name, p.name";
$stmt = $db->query($sql);
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>C03-02</title></head>
<body>
<h1>Overzicht leveranciers met hun producten</h1>
<table border="1" cellpadding="5">
<tr><th>Leverancier</th><th>Product</th></tr>
<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
<tr>
<td><?= htmlspecialchars($row['supplier_name']) ?></td>
<td><?= htmlspecialchars($row['product_name'] ?? '-') ?></td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
