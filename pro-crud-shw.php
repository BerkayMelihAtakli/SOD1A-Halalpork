<?php
session_start();
require_once "dbconnect.php";
require_once "product_helpers.php";

$groupStats = $db->query("
    SELECT
        SUM(categoryid IN (2,5,6,8,10) AND isactive='J') AS brood_count,
        MIN(CASE WHEN categoryid IN (2,5,6,8,10) AND isactive='J' THEN price END) AS brood_from,
        SUM(categoryid IN (3,4,7,9,11) AND isactive='J') AS broodjes_count,
        MIN(CASE WHEN categoryid IN (3,4,7,9,11) AND isactive='J' THEN price END) AS broodjes_from,
        SUM(categoryid = 1 AND isactive='J') AS gebak_count,
        MIN(CASE WHEN categoryid = 1 AND isactive='J' THEN price END) AS gebak_from
    FROM product
")->fetch(PDO::FETCH_ASSOC);

$vanDeWeek = $db->query("
    (SELECT p.productname, p.price, p.allergens, p.categoryid, c.name AS category, 'brood' AS groep
     FROM product p LEFT JOIN category c ON p.categoryid = c.ID
     WHERE p.categoryid IN (2,5,6,8,10) AND p.isactive='J'
     ORDER BY p.price DESC LIMIT 1)
    UNION ALL
    (SELECT p.productname, p.price, p.allergens, p.categoryid, c.name AS category, 'broodjes' AS groep
     FROM product p LEFT JOIN category c ON p.categoryid = c.ID
     WHERE p.categoryid IN (3,4,7,9,11) AND p.isactive='J'
     ORDER BY p.price DESC LIMIT 1)
    UNION ALL
    (SELECT p.productname, p.price, p.allergens, p.categoryid, c.name AS category, 'gebak' AS groep
     FROM product p LEFT JOIN category c ON p.categoryid = c.ID
     WHERE p.categoryid = 1 AND p.isactive='J'
     ORDER BY p.price DESC LIMIT 1)
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assortiment — The Bread Company</title>
    <link rel="stylesheet" href="company.css">
</head>
<body class="hp-page">
<div class="page">

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
                    <span>085 123 4567</span>
                </div>
            </div>
        </div>
        <div class="navbar">
            <div class="container nav-inner">
                <a href="index.php" class="logo">
                    <svg class="logo-icon" viewBox="0 0 20 32" fill="currentColor"><path d="M10 0 3 5v4l7-5 7 5V5l-7-5Zm0 8-7 5v4l7-5 7 5v-4l-7-5Zm0 8-7 5v4l7-5 7 5v-4l-7-5Zm0 8-7 5v3h14v-3l-7-5Z"/></svg>
                    <span class="logo-text">The Bread<br>Company</span>
                </a>
                <nav class="main-nav">
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
                        <li class="main-item active"><a href="pro-crud-shw.php">Assortiment</a></li>
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
        <section class="page-hero section-border">
            <div class="container page-hero-inner">
                <p class="eyebrow">Ons assortiment</p>
                <h1>Wat mag het zijn vandaag?</h1>
                <p class="intro-text">Kies uit onze versgebakken broden, knapperige broodjes of heerlijk gebak. Elke dag gemaakt met eerlijke ingrediënten en ambacht.</p>
            </div>
        </section>

        <section class="week-section section-border">
            <div class="container week-inner">
                <p class="eyebrow">Uitgelicht</p>
                <h2>Van de week</h2>
                <div class="week-grid">
                    <?php foreach ($vanDeWeek as $p): ?>
                    <div class="week-card">
                        <div class="week-card-img">
                            <img src="<?= getProductImage($p['productname'], (int)$p['categoryid']) ?>"
                                 alt="<?= htmlspecialchars($p['productname']) ?>">
                        </div>
                        <div class="week-card-body">
                            <span class="week-groep">&#9733; Aanrader — <?= htmlspecialchars(ucfirst($p['groep'])) ?></span>
                            <p class="week-name"><?= htmlspecialchars($p['productname']) ?></p>
                            <span class="week-cat"><?= htmlspecialchars(ucfirst($p['category'])) ?></span>
                            <div class="week-footer">
                                <span class="week-price">€<?= number_format((float)$p['price'], 2, ',', '') ?></span>
                                <span class="week-badge">Van de week</span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="groups-section">
            <div class="container">
                <p class="eyebrow">Categorieën</p>
                <h2>Waar bent u naar op zoek?</h2>
                <div class="groups-grid">

                    <a href="pro-brood.php" class="group-card">
                        <div class="group-card-top brood">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 11C3 7.686 7.03 5 12 5s9 2.686 9 6v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-1Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12v5a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 18v1m8-1v1"/>
                            </svg>
                        </div>
                        <div class="group-card-body">
                            <h3 class="group-card-title">Brood</h3>
                            <p class="group-card-desc">Volkoren, wit, bruin, buitenlands of gevuld — ons dagelijks vers gebakken brood voor elk smaakvoor­keur.</p>
                        </div>
                        <div class="group-card-footer">
                            <span class="group-meta"><strong><?= (int)$groupStats['brood_count'] ?></strong> producten &nbsp;·&nbsp; v.a. €<?= number_format((float)$groupStats['brood_from'], 2, ',', '') ?></span>
                            <span class="group-link">Bekijk <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-7 7 7-7 7"/></svg></span>
                        </div>
                    </a>

                    <a href="pro-broodjes.php" class="group-card">
                        <div class="group-card-top broodjes">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
                                <ellipse cx="12" cy="12" rx="9" ry="5.5"/>
                                <path stroke-linecap="round" d="M6 12c0-2 2.7-3.5 6-3.5s6 1.5 6 3.5"/>
                                <circle cx="9" cy="11" r=".8" fill="currentColor"/>
                                <circle cx="12" cy="10.2" r=".8" fill="currentColor"/>
                                <circle cx="15" cy="11" r=".8" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="group-card-body">
                            <h3 class="group-card-title">Broodjes &amp; Bollen</h3>
                            <p class="group-card-desc">Witte en bruine broodjes, krentenbollen en speciale varianten — perfect voor lunch of tussendoor.</p>
                        </div>
                        <div class="group-card-footer">
                            <span class="group-meta"><strong><?= (int)$groupStats['broodjes_count'] ?></strong> producten &nbsp;·&nbsp; v.a. €<?= number_format((float)$groupStats['broodjes_from'], 2, ',', '') ?></span>
                            <span class="group-link">Bekijk <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-7 7 7-7 7"/></svg></span>
                        </div>
                    </a>

                    <a href="pro-gebak.php" class="group-card">
                        <div class="group-card-top gebak">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 2a5 5 0 0 1 5 5v1h1a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h1V7a5 5 0 0 1 5-5Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 10V7a3 3 0 1 1 6 0v3"/>
                            </svg>
                        </div>
                        <div class="group-card-body">
                            <h3 class="group-card-title">Gebak &amp; Zoet</h3>
                            <p class="group-card-desc">Taarten, wafels, chocoladecreaties en ambachtelijk gebak voor elk moment dat extra verwennerij verdient.</p>
                        </div>
                        <div class="group-card-footer">
                            <span class="group-meta"><strong><?= (int)$groupStats['gebak_count'] ?></strong> producten &nbsp;·&nbsp; v.a. €<?= number_format((float)$groupStats['gebak_from'], 2, ',', '') ?></span>
                            <span class="group-link">Bekijk <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-7 7 7-7 7"/></svg></span>
                        </div>
                    </a>

                </div>
            </div>
        </section>
    </main>

</div>
</body>
</html>
