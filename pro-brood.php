<?php
require_once "dbconnect.php";

// All bread categories
$catIds = [2, 5, 6, 8, 10]; // bruin brood, buitenlands brood, gevuld brood, speciaal brood, wit brood
$in     = implode(',', $catIds);

$products = $db->query("
    SELECT p.productname, p.price, p.allergens, p.isactive, c.name AS category
    FROM product p
    LEFT JOIN category c ON p.categoryid = c.ID
    WHERE p.categoryid IN ($in)
    ORDER BY c.name, p.productname
")->fetchAll(PDO::FETCH_ASSOC);

// Group by category
$grouped = [];
foreach ($products as $p) {
    $grouped[$p['category']][] = $p;
}

$pageTitle  = 'Brood';
$activeNav  = 'assortiment';
$extraStyles = '<style>
    .breadcrumb { padding: 14px 0; font-size: 12px; color: #6c5f53; border-bottom: 1px solid #ede8e2; background: #faf7f3; }
    .breadcrumb a { color: #d89a18; text-decoration: none; }
    .breadcrumb a:hover { text-decoration: underline; }
    .breadcrumb span { margin: 0 6px; }
    .page-hero { border-bottom: 1px solid #ded6cc; }
    .page-hero-inner { padding: 44px 0; }
    .page-hero-inner h1 { font-size: 46px; line-height: 1; margin: 10px 0 12px; }
    .page-hero-inner .intro-text { max-width: 520px; }
    .catalog { padding-bottom: 64px; }
    .cat-section { padding-top: 44px; border-top: 1px solid #ede8e2; }
    .cat-section:first-child { border-top: none; }
    .cat-section h2 { font-size: 26px; margin: 0 0 20px; color: #2b1b10; }
    .menu-list { display: flex; flex-direction: column; gap: 0; border: 1px solid #ede8e2; border-radius: 10px; overflow: hidden; }
    .menu-item { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 18px 20px; background: #fff; border-bottom: 1px solid #ede8e2; transition: background .15s; }
    .menu-item:last-child { border-bottom: none; }
    .menu-item:hover { background: #faf6f0; }
    .menu-item.inactive { opacity: .45; }
    .menu-left { flex: 1; }
    .menu-name { font-family: Georgia, serif; font-size: 16px; font-weight: 700; color: #2b1b10; margin: 0 0 4px; }
    .menu-allergens { font-size: 11px; color: #8a7b70; }
    .menu-allergens strong { color: #b05a30; }
    .menu-right { display: flex; flex-direction: column; align-items: flex-end; gap: 5px; flex-shrink: 0; }
    .menu-price { font-family: Georgia, serif; font-size: 20px; font-weight: 700; color: #2b1b10; }
    .badge-ok  { font-size:10px; font-weight:700; padding:2px 8px; border-radius:20px; background:#f0faf1; color:#276b2e; border:1px solid #b8e2bc; }
    .badge-off { font-size:10px; font-weight:700; padding:2px 8px; border-radius:20px; background:#f5f5f5; color:#888; border:1px solid #ddd; }
    @media (max-width: 600px) { .container { padding-left: 16px; padding-right: 16px; } .page-hero-inner h1 { font-size: 32px; } }
</style>';
include "inc-header.php";
?>

<div class="breadcrumb">
    <div class="container">
        <a href="pro-crud-shw.php">Assortiment</a>
        <span>›</span>
        <strong>Brood</strong>
    </div>
</div>

<main>
    <section class="page-hero section-border">
        <div class="container page-hero-inner">
            <p class="eyebrow">Brood</p>
            <h1>Onze broden</h1>
            <p class="intro-text">Van luchtig wit tot stevig volkoren — ambachtelijk gebakken brood voor elke smaak en elk moment van de dag.</p>
        </div>
    </section>

    <div class="catalog container">
        <?php foreach ($grouped as $catName => $items): ?>
        <section class="cat-section">
            <h2><?= htmlspecialchars(ucfirst($catName)) ?></h2>
            <div class="menu-list">
                <?php foreach ($items as $p): ?>
                <div class="menu-item<?= $p['isactive'] === 'N' ? ' inactive' : '' ?>">
                    <div class="menu-left">
                        <p class="menu-name"><?= htmlspecialchars($p['productname']) ?></p>
                        <?php $allerg = trim($p['allergens'] ?? ''); if ($allerg): ?>
                        <p class="menu-allergens"><strong>Allergenen:</strong> <?= htmlspecialchars($allerg) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="menu-right">
                        <span class="menu-price">€<?= number_format((float)$p['price'], 2, ',', '') ?></span>
                        <?= $p['isactive'] === 'J'
                            ? '<span class="badge-ok">Beschikbaar</span>'
                            : '<span class="badge-off">Niet actief</span>' ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endforeach; ?>
    </div>
</main>

</div>
</body>
</html>
