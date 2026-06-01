<?php
require_once "dbconnect.php";

// Categories with counts + lowest price
$categories = $db->query("
    SELECT c.ID, c.name,
           COUNT(p.ID) AS total,
           SUM(p.isactive = 'J') AS active,
           MIN(CASE WHEN p.isactive = 'J' THEN p.price END) AS from_price
    FROM category c
    LEFT JOIN product p ON p.categoryid = c.ID
    GROUP BY c.ID, c.name
    ORDER BY c.name
")->fetchAll(PDO::FETCH_ASSOC);

// Van de week: 4 active gebak products (highest price = premium)
$featured = $db->query("
    SELECT p.ID, p.productname, p.allergens, p.price,
           c.name AS category, s.company AS supplier
    FROM product p
    LEFT JOIN category c ON p.categoryid = c.ID
    LEFT JOIN supplier s ON p.supplierid = s.ID
    WHERE p.isactive = 'J' AND p.categoryid = 1
    ORDER BY p.price DESC
    LIMIT 4
")->fetchAll(PDO::FETCH_ASSOC);

// All active products grouped (we'll group in PHP)
$allProducts = $db->query("
    SELECT p.ID, p.productname, p.ingredients, p.allergens, p.price, p.isactive,
           c.ID AS cat_id, c.name AS category, s.company AS supplier
    FROM product p
    LEFT JOIN category c ON p.categoryid = c.ID
    LEFT JOIN supplier s ON p.supplierid = s.ID
    ORDER BY c.name, p.productname
")->fetchAll(PDO::FETCH_ASSOC);

// Group products by category
$grouped = [];
foreach ($allProducts as $p) {
    $grouped[$p['cat_id']]['name']       = $p['category'];
    $grouped[$p['cat_id']]['products'][] = $p;
}

// Icons per category slug (matched by keyword)
function catIcon(string $name): string {
    $n = mb_strtolower($name);
    if (str_contains($n, 'gebak'))            return '<path stroke-linecap="round" stroke-linejoin="round" d="M12 2a5 5 0 0 1 5 5v1h1a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h1V7a5 5 0 0 1 5-5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 10V7a3 3 0 1 1 6 0v3"/>';
    if (str_contains($n, 'krentenbollen'))    return '<circle cx="12" cy="12" r="8"/><circle cx="9" cy="11" r="1" fill="currentColor"/><circle cx="13" cy="10" r="1" fill="currentColor"/><circle cx="11" cy="14" r="1" fill="currentColor"/>';
    if (str_contains($n, 'broodjes') || str_contains($n, 'bollen')) return '<ellipse cx="12" cy="12" rx="9" ry="6"/><path stroke-linecap="round" d="M6 12c0-2 2.7-4 6-4s6 2 6 4"/>';
    if (str_contains($n, 'gevuld'))           return '<path stroke-linecap="round" stroke-linejoin="round" d="M4 12c0-4.418 3.582-8 8-8s8 3.582 8 8-3.582 8-8 8-8-3.582-8-8Z"/><path stroke-linecap="round" d="M8 12c0-2.2 1.8-4 4-4s4 1.8 4 4"/>';
    // default bread loaf
    return '<path stroke-linecap="round" stroke-linejoin="round" d="M3 11C3 7.686 7.03 5 12 5s9 2.686 9 6v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-1Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 12v5a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-5"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v1m6-1v1"/>';
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assortiment — The Bread Company</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* ─── Page hero ─── */
        .page-hero { border-bottom: 1px solid #ded6cc; }
        .page-hero-inner {
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            gap: 24px;
            padding-top: 40px;
            padding-bottom: 40px;
        }
        .page-hero-inner h1 { font-size: 46px; line-height: 1; margin: 10px 0 0; }
        .page-hero-inner .intro-text { margin-top: 12px; max-width: 520px; }
        .hero-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 6px;
            text-align: right;
        }
        .hero-meta-num {
            font-family: Georgia, serif;
            font-size: 42px;
            font-weight: 700;
            color: #d89a18;
            line-height: 1;
        }
        .hero-meta-label { font-size: 11px; color: #6c5f53; }

        /* ─── Category nav strip ─── */
        .cat-nav { background: #f5f1eb; border-bottom: 1px solid #ded6cc; position: sticky; top: 0; z-index: 100; }
        .cat-nav-inner {
            display: flex;
            gap: 4px;
            overflow-x: auto;
            padding: 10px 0;
            scrollbar-width: none;
        }
        .cat-nav-inner::-webkit-scrollbar { display: none; }
        .cat-nav-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 16px;
            border-radius: 24px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            text-decoration: none;
            color: #54463b;
            border: 1px solid transparent;
            transition: 0.15s;
            flex-shrink: 0;
        }
        .cat-nav-btn svg { width: 14px; height: 14px; }
        .cat-nav-btn:hover { background: #ede5da; color: #2b1b10; }
        .cat-nav-btn.active { background: #d89a18; color: #fff; border-color: #d89a18; }

        /* ─── Featured / Van de Week ─── */
        .featured-section { border-bottom: 1px solid #ded6cc; background: #231209; }
        .featured-inner { padding-top: 52px; padding-bottom: 52px; }
        .featured-inner .eyebrow { color: #d89a18; }
        .featured-inner > h2 { color: #f6efe6; font-size: 38px; margin: 10px 0 32px; }
        .featured-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        .featured-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .featured-card:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(0,0,0,0.3); }
        .featured-card-top {
            height: 110px;
            background: linear-gradient(135deg, rgba(216,154,24,0.18), rgba(216,154,24,0.05));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d89a18;
        }
        .featured-card-top svg { width: 48px; height: 48px; opacity: 0.7; }
        .featured-card-body { padding: 16px; }
        .featured-tag {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #d89a18;
        }
        .featured-name {
            font-family: Georgia, serif;
            font-size: 16px;
            font-weight: 700;
            color: #f6efe6;
            margin: 6px 0 4px;
            line-height: 1.2;
        }
        .featured-supplier { font-size: 11px; color: #8a7b70; }
        .featured-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px 14px;
        }
        .featured-price {
            font-family: Georgia, serif;
            font-size: 22px;
            font-weight: 700;
            color: #d89a18;
        }
        .badge-week {
            font-size: 10px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
            background: rgba(216,154,24,0.2);
            color: #d89a18;
            border: 1px solid rgba(216,154,24,0.35);
        }

        /* ─── Category sections ─── */
        .catalog { padding-bottom: 64px; }

        .cat-section { padding-top: 52px; border-top: 1px solid #ded6cc; }
        .cat-section:first-child { border-top: 0; padding-top: 52px; }

        .cat-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .cat-section-title {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .cat-icon-wrap {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f7efe0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d89a18;
            flex-shrink: 0;
        }
        .cat-icon-wrap svg { width: 20px; height: 20px; }
        .cat-section-header h2 { font-size: 28px; margin: 0; }
        .cat-from-price { font-size: 12px; color: #6c5f53; }

        /* ─── Product grid ─── */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }
        .product-card {
            background: #fff;
            border: 1px solid #ede8e2;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .product-card:hover { box-shadow: 0 6px 20px rgba(35,18,9,0.09); transform: translateY(-2px); }
        .product-card.inactive { opacity: 0.5; }

        .product-card-top {
            height: 80px;
            background: linear-gradient(135deg, #faf4e8, #f3ece0);
            border-radius: 10px 10px 0 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d89a18;
        }
        .product-card-top svg { width: 36px; height: 36px; opacity: 0.5; }

        .product-card-body { padding: 14px 14px 0; flex: 1; }
        .product-name {
            font-family: Georgia, serif;
            font-size: 15px;
            font-weight: 700;
            color: #2b1b10;
            line-height: 1.2;
            margin: 0 0 5px;
        }
        .product-supplier { font-size: 11px; color: #a89a8e; margin-bottom: 8px; }
        .product-allergens {
            font-size: 10.5px;
            color: #7a6358;
            line-height: 1.4;
            padding: 6px 8px;
            background: #fdf8f2;
            border-radius: 5px;
            border-left: 2px solid #e8d09a;
            margin-bottom: 4px;
        }
        .allergen-label { font-weight: 700; color: #c0612a; }

        .product-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px 12px;
            margin-top: auto;
        }
        .product-price {
            font-family: Georgia, serif;
            font-size: 19px;
            font-weight: 700;
            color: #2b1b10;
        }
        .badge-ok {
            font-size: 10px; font-weight: 700; padding: 2px 8px;
            border-radius: 20px;
            background: #f0faf1; color: #276b2e; border: 1px solid #b8e2bc;
        }
        .badge-off {
            font-size: 10px; font-weight: 700; padding: 2px 8px;
            border-radius: 20px;
            background: #f5f5f5; color: #888; border: 1px solid #ddd;
        }

        /* ─── Responsive ─── */
        @media (max-width: 1100px) {
            .featured-grid, .product-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 900px) {
            .container { padding-left: 20px; padding-right: 20px; }
            .page-hero-inner { grid-template-columns: 1fr; }
            .hero-meta { align-items: flex-start; text-align: left; }
            .featured-grid, .product-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 540px) {
            .product-grid { grid-template-columns: 1fr; }
            .featured-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="page">

    <!-- ─── Header ─── -->
    <header>
        <div class="topbar">
            <div class="container topbar-inner">
                <div class="topbar-item">
                    <svg class="small-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2 4 6v6c0 5.25 3.438 10.125 8 11.625C16.563 22.125 20 17.25 20 12V6l-8-4Z"/></svg>
                    <span>Ambachtelijk gebakken in Nederland</span>
                </div>
                <div class="topbar-item hide-mobile">
                    <svg class="small-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M10 17.25 4.75 12 6.16 10.59 10 14.42 17.84 6.59 19.25 8 10 17.25Z"/></svg>
                    <span>Voor 15:00 besteld, morgen in huis</span>
                </div>
                <div class="topbar-item hide-mobile">
                    <svg class="small-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79a15.054 15.054 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1-.24c1.12.37 2.33.57 3.59.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.06 21 3 13.94 3 5a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.26.2 2.47.57 3.59a1 1 0 0 1-.24 1l-2.2 2.2Z"/></svg>
                    <span>Bel ons: 085 123 4567</span>
                </div>
            </div>
        </div>

        <div class="navbar">
            <div class="container nav-inner">
                <a href="index.php" class="logo" aria-label="The Bread Company home">
                    <svg class="logo-icon" viewBox="0 0 20 32" fill="currentColor"><path d="M10 0 3 5v4l7-5 7 5V5l-7-5Zm0 8-7 5v4l7-5 7 5v-4l-7-5Zm0 8-7 5v4l7-5 7 5v-4l-7-5Zm0 8-7 5v3h14v-3l-7-5Z"/></svg>
                    <span class="logo-text">The Bread<br>Company</span>
                </a>
                <nav class="main-nav" aria-label="Hoofdnavigatie">
                    <ul class="main-menu">
                        <li class="main-item"><a href="index.php">Home</a></li>
                        <li class="main-item"><a href="#">Bedrijf</a>
                            <ul>
                                <li><a href="stat-ecofriend.php" class="scndlvl">Eco-vriendelijk</a></li>
                                <li><a href="stat-employees.php" class="scndlvl">Medewerkers</a></li>
                                <li><a href="stat-targets.php" class="scndlvl">Doelstellingen</a></li>
                                <li><a href="stat-history.php" class="scndlvl">Geschiedenis</a></li>
                            </ul>
                        </li>
                        <li class="main-item active"><a href="#">Overzicht</a>
                            <ul>
                                <li><a href="cat-crud-shw.php" class="scndlvl">Categorieën</a></li>
                                <li><a href="cli-crud-shw.php" class="scndlvl">Klanten</a></li>
                                <li><a href="sup-crud-shw.php" class="scndlvl">Leveranciers</a></li>
                                <li><a href="pro-crud-shw.php" class="scndlvl">Produkten</a></li>
                                <li><a href="pur-crud-shw.php" class="scndlvl">Aankopen</a></li>
                                <li><a href="cou-crud-shw.php" class="scndlvl">Landen</a></li>
                            </ul>
                        </li>
                        <li class="main-item"><a href="#">Informatie</a>
                            <ul>
                                <li><a href="#" class="scndlvl">Aantal</a>
                                    <ul>
                                        <li><a href="count-supl-per-country.php" class="thrdlvl">Lev per land</a></li>
                                        <li><a href="count-prod-per-cat.php" class="thrdlvl">Prod per catagr</a></li>
                                        <li><a href="count-purch-per-client.php" class="thrdlvl">Aankoop per klant</a></li>
                                        <li><a href="count-purline-per-purch.php" class="thrdlvl">Regels per aankoop</a></li>
                                        <li><a href="count-purch-per-prod.php" class="thrdlvl">Aankoop per prod</a></li>
                                    </ul>
                                </li>
                                <li><a href="#" class="scndlvl">Gemiddeld</a>
                                    <ul>
                                        <li><a href="avg-prodprice-per-supl.php" class="thrdlvl">Prodprijs-lev</a></li>
                                        <li><a href="avg-prodprice-per-cat.php" class="thrdlvl">Prodprijs-catgr</a></li>
                                        <li><a href="total-price-per-purch.php" class="thrdlvl">Tot prijs-aankoop</a></li>
                                    </ul>
                                </li>
                                <li><a href="#" class="scndlvl">Samengesteld</a>
                                    <ul>
                                        <li><a href="shw-purchdet-per-prod.php" class="thrdlvl">Aankoopdet.-prod</a></li>
                                        <li><a href="shw-prod-per-supl.php" class="thrdlvl">Produkt-lev</a></li>
                                        <li><a href="shw-prod-per-cat.php" class="thrdlvl">Produkt-cat</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="main-item"><a href="#">Onderhoud</a>
                            <ul>
                                <li><a href="cat-crud-get.php" class="scndlvl">Categorieen</a></li>
                                <li><a href="cli-crud-get.php" class="scndlvl">Klanten</a></li>
                                <li><a href="sup-crud-get.php" class="scndlvl">Leveranciers</a></li>
                                <li><a href="pro-crud-get.php" class="scndlvl">Producten</a></li>
                                <li><a href="pur-crud-get.php" class="scndlvl">Aankopen</a></li>
                                <li><a href="cou-crud-get.php" class="scndlvl">Landen</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <a href="pro-crud-shw.php" class="btn primary">Bestel nu</a>
            </div>
        </div>
    </header>

    <main>

        <!-- ─── Page hero ─── -->
        <section class="page-hero section-border">
            <div class="container page-hero-inner">
                <div>
                    <p class="eyebrow">Ons assortiment</p>
                    <h1>Dagelijks vers uit de oven</h1>
                    <p class="intro-text">
                        Van knapperige baguettes tot ambachtelijke volkoren broden en verwennerij uit de banketbakkerij —
                        alles met liefde gemaakt, elke dag opnieuw.
                    </p>
                </div>
                <div class="hero-meta">
                    <span class="hero-meta-num"><?= count($allProducts) ?></span>
                    <span class="hero-meta-label">producten in ons assortiment</span>
                    <span class="hero-meta-num" style="margin-top:16px"><?= count($categories) ?></span>
                    <span class="hero-meta-label">categorieën</span>
                </div>
            </div>
        </section>

        <!-- ─── Sticky category nav ─── -->
        <div class="cat-nav section-border">
            <div class="container cat-nav-inner">
                <a href="#van-de-week" class="cat-nav-btn active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    Van de week
                </a>
                <?php foreach ($categories as $cat): ?>
                <a href="#cat-<?= (int)$cat['ID'] ?>" class="cat-nav-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <?= catIcon($cat['name']) ?>
                    </svg>
                    <?= htmlspecialchars(ucfirst($cat['name'])) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- ─── Van de week ─── -->
        <section class="featured-section" id="van-de-week">
            <div class="container featured-inner">
                <p class="eyebrow">Uitgelicht</p>
                <h2>Van de week</h2>
                <div class="featured-grid">
                    <?php foreach ($featured as $p): ?>
                    <article class="featured-card">
                        <div class="featured-card-top">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 2a5 5 0 0 1 5 5v1h1a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h1V7a5 5 0 0 1 5-5Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 10V7a3 3 0 1 1 6 0v3"/>
                            </svg>
                        </div>
                        <div class="featured-card-body">
                            <span class="featured-tag"><?= htmlspecialchars(ucfirst($p['category'])) ?></span>
                            <h3 class="featured-name"><?= htmlspecialchars($p['productname']) ?></h3>
                            <p class="featured-supplier"><?= htmlspecialchars($p['supplier'] ?? '') ?></p>
                        </div>
                        <div class="featured-footer">
                            <span class="featured-price">€<?= number_format((float)$p['price'], 2, ',', '') ?></span>
                            <span class="badge-week">&#9733; Van de week</span>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- ─── Products by category ─── -->
        <div class="catalog container">
            <?php foreach ($grouped as $catId => $group): ?>
            <?php
                $catMeta = array_values(array_filter($categories, fn($c) => (int)$c['ID'] === (int)$catId));
                $catMeta = $catMeta[0] ?? null;
            ?>
            <section class="cat-section" id="cat-<?= (int)$catId ?>">
                <div class="cat-section-header">
                    <div class="cat-section-title">
                        <div class="cat-icon-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <?= catIcon($group['name']) ?>
                            </svg>
                        </div>
                        <h2><?= htmlspecialchars(ucfirst($group['name'])) ?></h2>
                    </div>
                    <?php if ($catMeta && $catMeta['from_price']): ?>
                    <span class="cat-from-price">
                        <?= (int)$catMeta['active'] ?> producten &nbsp;·&nbsp; vanaf
                        <strong>€<?= number_format((float)$catMeta['from_price'], 2, ',', '') ?></strong>
                    </span>
                    <?php endif; ?>
                </div>

                <div class="product-grid">
                    <?php foreach ($group['products'] as $p): ?>
                    <article class="product-card<?= $p['isactive'] === 'N' ? ' inactive' : '' ?>">
                        <div class="product-card-top">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
                                <?= catIcon($group['name']) ?>
                            </svg>
                        </div>
                        <div class="product-card-body">
                            <h3 class="product-name"><?= htmlspecialchars($p['productname']) ?></h3>
                            <p class="product-supplier"><?= htmlspecialchars($p['supplier'] ?? '—') ?></p>
                            <?php
                                $allerg = trim($p['allergens'] ?? '');
                                if ($allerg !== ''):
                            ?>
                            <div class="product-allergens">
                                <span class="allergen-label">Allergenen: </span><?= htmlspecialchars($allerg) ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="product-card-footer">
                            <span class="product-price">€<?= number_format((float)$p['price'], 2, ',', '') ?></span>
                            <?php if ($p['isactive'] === 'J'): ?>
                                <span class="badge-ok">Beschikbaar</span>
                            <?php else: ?>
                                <span class="badge-off">Niet actief</span>
                            <?php endif; ?>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endforeach; ?>
        </div>

    </main>

</div>

<script>
    // Highlight active category in sticky nav on scroll
    const sections = document.querySelectorAll('[id^="cat-"], #van-de-week');
    const navBtns  = document.querySelectorAll('.cat-nav-btn');

    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                navBtns.forEach(b => b.classList.remove('active'));
                const active = document.querySelector(`.cat-nav-btn[href="#${e.target.id}"]`);
                if (active) {
                    active.classList.add('active');
                    active.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                }
            }
        });
    }, { threshold: 0.25 });

    sections.forEach(s => observer.observe(s));
</script>
</body>
</html>
