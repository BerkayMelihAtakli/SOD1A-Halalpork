<?php
require_once "dbconnect.php";

// Stats per group
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

// Van de week: 1 product per group (highest price, active)
$vanDeWeek = $db->query("
    (SELECT p.productname, p.price, p.allergens, c.name AS category, 'brood' AS groep
     FROM product p LEFT JOIN category c ON p.categoryid = c.ID
     WHERE p.categoryid IN (2,5,6,8,10) AND p.isactive='J'
     ORDER BY p.price DESC LIMIT 1)
    UNION ALL
    (SELECT p.productname, p.price, p.allergens, c.name AS category, 'broodjes' AS groep
     FROM product p LEFT JOIN category c ON p.categoryid = c.ID
     WHERE p.categoryid IN (3,4,7,9,11) AND p.isactive='J'
     ORDER BY p.price DESC LIMIT 1)
    UNION ALL
    (SELECT p.productname, p.price, p.allergens, c.name AS category, 'gebak' AS groep
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
    <link rel="stylesheet" href="style.css">
    <style>
        .page-hero { border-bottom: 1px solid #ded6cc; }
        .page-hero-inner { padding: 48px 0; }
        .page-hero-inner h1 { font-size: 50px; line-height: 1; margin: 10px 0 12px; }
        .page-hero-inner .intro-text { max-width: 520px; }

        /* Van de week */
        .week-section { background: #231209; border-bottom: 1px solid #ded6cc; }
        .week-inner { padding: 52px 0; }
        .week-inner > .eyebrow { color: #d89a18; }
        .week-inner > h2 { color: #f6efe6; font-size: 36px; margin: 10px 0 32px; }
        .week-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .week-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .week-groep {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .07em; color: #d89a18;
        }
        .week-name {
            font-family: Georgia, serif; font-size: 18px; font-weight: 700;
            color: #f6efe6; line-height: 1.2;
        }
        .week-cat { font-size: 11px; color: #8a7b70; }
        .week-footer { display: flex; align-items: center; justify-content: space-between; margin-top: 6px; }
        .week-price { font-family: Georgia, serif; font-size: 24px; font-weight: 700; color: #d89a18; }
        .week-badge {
            font-size: 10px; font-weight: 700; padding: 3px 9px; border-radius: 20px;
            background: rgba(216,154,24,0.2); color: #d89a18; border: 1px solid rgba(216,154,24,0.35);
        }

        /* Category cards */
        .groups-section { padding: 56px 0 64px; }
        .groups-section > .container > .eyebrow { margin-bottom: 10px; }
        .groups-section > .container > h2 { font-size: 38px; margin: 0 0 36px; }
        .groups-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }

        .group-card {
            border: 1px solid #ded6cc;
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
            display: flex;
            flex-direction: column;
            transition: box-shadow .2s, transform .2s;
            text-decoration: none;
            color: inherit;
        }
        .group-card:hover { box-shadow: 0 10px 28px rgba(35,18,9,.10); transform: translateY(-3px); }

        .group-card-top {
            height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 10px;
        }
        .group-card-top.brood    { background: linear-gradient(135deg, #fdf3dc, #f5e6c0); }
        .group-card-top.broodjes { background: linear-gradient(135deg, #f0f8e8, #dcefd0); }
        .group-card-top.gebak    { background: linear-gradient(135deg, #fde8f0, #f5cee0); }

        .group-card-top svg { width: 52px; height: 52px; }
        .group-card-top.brood    svg { color: #b87b1a; }
        .group-card-top.broodjes svg { color: #4a7c35; }
        .group-card-top.gebak    svg { color: #a8346a; }

        .group-card-body { padding: 22px 22px 18px; flex: 1; }
        .group-card-title { font-family: Georgia, serif; font-size: 24px; font-weight: 700; color: #2b1b10; margin: 0 0 8px; }
        .group-card-desc { font-size: 13px; line-height: 1.55; color: #6c5f53; margin: 0; }

        .group-card-footer {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 22px; border-top: 1px solid #ede8e2;
        }
        .group-meta { font-size: 12px; color: #6c5f53; }
        .group-meta strong { color: #2b1b10; }
        .group-link {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 13px; font-weight: 600; color: #d89a18;
        }
        .group-link svg { width: 16px; height: 16px; }

        @media (max-width: 900px) {
            .container { padding-left: 20px; padding-right: 20px; }
            .week-grid, .groups-grid { grid-template-columns: 1fr; }
            .page-hero-inner h1 { font-size: 36px; }
        }
    </style>
</head>
<body>
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
        <!-- Hero -->
        <section class="page-hero section-border">
            <div class="container page-hero-inner">
                <p class="eyebrow">Ons assortiment</p>
                <h1>Wat mag het zijn vandaag?</h1>
                <p class="intro-text">Kies uit onze versgebakken broden, knapperige broodjes of heerlijk gebak. Elke dag gemaakt met eerlijke ingrediënten en ambacht.</p>
            </div>
        </section>

        <!-- Van de week -->
        <section class="week-section section-border">
            <div class="container week-inner">
                <p class="eyebrow">Uitgelicht</p>
                <h2>Van de week</h2>
                <div class="week-grid">
                    <?php foreach ($vanDeWeek as $p): ?>
                    <div class="week-card">
                        <span class="week-groep">&#9733; Aanrader — <?= htmlspecialchars(ucfirst($p['groep'])) ?></span>
                        <p class="week-name"><?= htmlspecialchars($p['productname']) ?></p>
                        <span class="week-cat"><?= htmlspecialchars(ucfirst($p['category'])) ?></span>
                        <div class="week-footer">
                            <span class="week-price">€<?= number_format((float)$p['price'], 2, ',', '') ?></span>
                            <span class="week-badge">Van de week</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Category groups -->
        <section class="groups-section">
            <div class="container">
                <p class="eyebrow">Categorieën</p>
                <h2>Waar bent u naar op zoek?</h2>
                <div class="groups-grid">

                    <!-- Brood -->
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

                    <!-- Broodjes & Bollen -->
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

                    <!-- Gebak -->
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
