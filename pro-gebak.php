<?php
require_once "dbconnect.php";

$products = $db->query("
    SELECT p.productname, p.price, p.allergens, p.isactive, p.ingredients
    FROM product p
    WHERE p.categoryid = 1
    ORDER BY p.productname
")->fetchAll(PDO::FETCH_ASSOC);

// Split into two visual groups: sweet (all) — optionally sub-split by price range
$premium  = array_filter($products, fn($p) => (float)$p['price'] >= 8);
$regulier = array_filter($products, fn($p) => (float)$p['price'] < 8);

$pageTitle  = 'Gebak & Zoet';
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
    .cat-section-header { margin-bottom: 20px; }
    .cat-section h2 { font-size: 26px; margin: 0 0 4px; color: #2b1b10; }
    .cat-section-sub { font-size: 12px; color: #6c5f53; }
    .menu-list { display: flex; flex-direction: column; gap: 0; border: 1px solid #ede8e2; border-radius: 10px; overflow: hidden; }
    .menu-item { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; padding: 18px 20px; background: #fff; border-bottom: 1px solid #ede8e2; transition: background .15s; }
    .menu-item:last-child { border-bottom: none; }
    .menu-item:hover { background: #fdf8f4; }
    .menu-item.inactive { opacity: .45; }
    .menu-left { flex: 1; }
    .menu-name { font-family: Georgia, serif; font-size: 16px; font-weight: 700; color: #2b1b10; margin: 0 0 4px; }
    .menu-desc { font-size: 12px; color: #8a7b70; line-height: 1.5; margin: 0 0 4px; max-width: 540px; }
    .menu-allergens { font-size: 11px; color: #8a7b70; margin: 0; }
    .menu-allergens strong { color: #b05a30; }
    .menu-right { display: flex; flex-direction: column; align-items: flex-end; gap: 5px; flex-shrink: 0; padding-top: 2px; }
    .menu-price { font-family: Georgia, serif; font-size: 20px; font-weight: 700; color: #2b1b10; }
    .badge-ok  { font-size:10px; font-weight:700; padding:2px 8px; border-radius:20px; background:#f0faf1; color:#276b2e; border:1px solid #b8e2bc; }
    .badge-off { font-size:10px; font-weight:700; padding:2px 8px; border-radius:20px; background:#f5f5f5; color:#888; border:1px solid #ddd; }
    .badge-premium { font-size:10px; font-weight:700; padding:2px 8px; border-radius:20px; background:#fdf3dc; color:#b87b1a; border:1px solid #e8d09a; }
    @media (max-width: 600px) { .container { padding-left: 16px; padding-right: 16px; } .page-hero-inner h1 { font-size: 32px; } .menu-desc { display: none; } }
</style>';
include "inc-header.php";
?>

<div class="breadcrumb">
    <div class="container">
        <a href="pro-crud-shw.php">Assortiment</a>
        <span>›</span>
        <strong>Gebak &amp; Zoet</strong>
    </div>
</div>

<main>
    <section class="page-hero section-border">
        <div class="container page-hero-inner">
            <p class="eyebrow">Gebak &amp; Zoet</p>
            <h1>Verwennerij van de bakker</h1>
            <p class="intro-text">Ambachtelijk gebak, taarten en zoetigheden — met liefde gemaakt voor elk moment dat iets bijzonders verdient.</p>
        </div>
    </section>

    <div class="catalog container">

        <?php if (!empty($premium)): ?>
        <section class="cat-section">
            <div class="cat-section-header">
                <h2>&#9733; Premium gebak</h2>
                <p class="cat-section-sub">Bijzondere creaties voor speciale gelegenheden</p>
            </div>
            <div class="menu-list">
                <?php foreach ($premium as $p): ?>
                <div class="menu-item<?= $p['isactive'] === 'N' ? ' inactive' : '' ?>">
                    <div class="menu-left">
                        <p class="menu-name"><?= htmlspecialchars($p['productname']) ?></p>
                        <?php
                            $ing = trim($p['ingredients'] ?? '');
                            if ($ing && strlen($ing) > 5):
                                $short = mb_strlen($ing) > 100 ? mb_substr($ing, 0, 100) . '…' : $ing;
                        ?>
                        <p class="menu-desc"><?= htmlspecialchars($short) ?></p>
                        <?php endif; ?>
                        <?php $allerg = trim($p['allergens'] ?? ''); if ($allerg): ?>
                        <p class="menu-allergens"><strong>Allergenen:</strong> <?= htmlspecialchars($allerg) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="menu-right">
                        <span class="menu-price">€<?= number_format((float)$p['price'], 2, ',', '') ?></span>
                        <?php if ($p['isactive'] === 'J'): ?>
                            <span class="badge-premium">&#9733; Premium</span>
                        <?php else: ?>
                            <span class="badge-off">Niet actief</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if (!empty($regulier)): ?>
        <section class="cat-section">
            <div class="cat-section-header">
                <h2>Dagelijks gebak</h2>
                <p class="cat-section-sub">Heerlijk voor elke dag</p>
            </div>
            <div class="menu-list">
                <?php foreach ($regulier as $p): ?>
                <div class="menu-item<?= $p['isactive'] === 'N' ? ' inactive' : '' ?>">
                    <div class="menu-left">
                        <p class="menu-name"><?= htmlspecialchars($p['productname']) ?></p>
                        <?php
                            $ing = trim($p['ingredients'] ?? '');
                            if ($ing && strlen($ing) > 5):
                                $short = mb_strlen($ing) > 100 ? mb_substr($ing, 0, 100) . '…' : $ing;
                        ?>
                        <p class="menu-desc"><?= htmlspecialchars($short) ?></p>
                        <?php endif; ?>
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
        <?php endif; ?>

    </div>
</main>

</div>
</body>
</html>
