<?php
$navItems = ["Home", "Assortiment", "Over ons", "Duurzaamheid", "Contact"];

$highlights = [
    "Natuurlijke ingrediënten",
    "Zonder kunstmatige toevoegingen",
    "Duurzaam & verantwoord"
];

$features = [
    [
        "title" => "Dagelijks vers",
        "description" => "Elke ochtend vers gebakken in onze eigen bakkerij.",
        "icon" => '<svg aria-hidden="true" class="feature-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3 4 7v10l8 4 8-4V7l-8-4Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v14"/></svg>'
    ],
    [
        "title" => "Eerlijke ingrediënten",
        "description" => "Alleen de beste ingrediënten, zonder onnodige toevoegingen.",
        "icon" => '<svg aria-hidden="true" class="feature-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19c4 0 7-3.134 7-7 0-1.755-.617-3.36-1.643-4.616C15.942 5.67 14.11 5 12 5s-3.942.67-5.357 2.384A6.965 6.965 0 0 0 5 12c0 3.866 3 7 7 7Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M8.5 13c1.2 0 2-.8 2-2.2 0-1.1-.7-2.2-2-3.3-1.3 1.1-2 2.2-2 3.3 0 1.4.8 2.2 2 2.2Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15.5 15c1 0 1.8-.7 1.8-1.8 0-.9-.6-1.8-1.8-2.7-1.2.9-1.8 1.8-1.8 2.7 0 1.1.8 1.8 1.8 1.8Z"/></svg>'
    ],
    [
        "title" => "Met liefde gemaakt",
        "description" => "Ambachtelijk bereid met passie voor smaak en kwaliteit.",
        "icon" => '<svg aria-hidden="true" class="feature-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20s-6.5-4.35-8.5-8A4.96 4.96 0 0 1 8 5c1.6 0 3 1 4 2.2C13 6 14.4 5 16 5a4.96 4.96 0 0 1 4.5 7c-2 3.65-8.5 8-8.5 8Z"/></svg>'
    ],
    [
        "title" => "Snelle levering",
        "description" => "Voor 15:00 besteld, morgen bij jou thuis.",
        "icon" => '<svg aria-hidden="true" class="feature-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h11v8H3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 10h3l4 3v2h-7z"/><path stroke-linecap="round" stroke-linejoin="round" d="M7 18a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm11 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Z"/></svg>'
    ]
];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Bread Company</title>
    <link rel="stylesheet" href="style.css">
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
                <div class="topbar-item">
                    <div class="phone hide-mobile">
                        <svg class="small-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79a15.054 15.054 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1-.24c1.12.37 2.33.57 3.59.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.06 21 3 13.94 3 5a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.26.2 2.47.57 3.59a1 1 0 0 1-.24 1l-2.2 2.2Z"/></svg>
                        <span>Bel ons: 085 123 4567</span>
                    </div>
                    <svg class="small-icon" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="9"/></svg>
                    <svg class="small-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
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
                        <li class="main-item active"><a href="index.php" aria-current="page">Home</a></li>

                        <li class="main-item"><a href="#">Bedrijf</a>
                            <ul>
                                <li><a href="stat-ecofriend.php" class="scndlvl">Eco-vriendelijk</a></li>
                                <li><a href="stat-employees.php" class="scndlvl">Medewerkers</a></li>
                                <li><a href="stat-targets.php" class="scndlvl">Doelstellingen</a></li>
                                <li><a href="stat-history.php" class="scndlvl">Geschiedenis</a></li>
                            </ul>
                        </li>

                        <li class="main-item"><a href="#">Overzicht</a>
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
                                <li><a href="pro-active-get.php" class="scndlvl">Producten actief/inactief</a></li>
                                <li><a href="pur-crud-get.php" class="scndlvl">Aankopen</a></li>
                                <li><a href="cou-crud-get.php" class="scndlvl">Landen</a></li>
                            </ul>
                        </li>

                        <li class="main-item"><a href="#">Account</a>
                            <ul>
                                <li><a href="login.php" class="scndlvl">Login</a></li>
                                <li><a href="change-password.php" class="scndlvl">Wachtwoord wijzigen</a></li>
                                <li><a href="logout.php" class="scndlvl">Uitloggen</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>

                <a href="pro-crud-shw.php" class="btn primary">Bestel nu</a>
            </div>
        </div>
    </header>

    <main>
        <?php if (isset($_GET['msg'])) { ?>
            <div class="homepage-message"><?php echo htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
        <section class="hero section-border">
            <div class="container hero-grid">
                <div class="hero-content">
                    <p class="eyebrow">Ambacht. Smaak. Kwaliteit.</p>
                    <h1>Versgebakken met passie en vakmanschap</h1>
                    <div class="button-row">
                        <a href="pro-crud-shw.php" class="btn primary icon-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M7 7V5h10v2m-9 4h8m-8 4h5"/></svg>
                            Bekijk ons assortiment
                        </a>
                        <a href="stat-ecofriend.php" class="btn secondary icon-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 2.5"/></svg>
                            Over duurzaamheid
                        </a>
                    </div>
                    <ul class="highlights">
                        <?php foreach ($highlights as $item): ?>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9"/></svg>
                                <span><?= htmlspecialchars($item) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <p class="intro-text">Elke dag bakken wij met liefde de heerlijkste broden, knapperige broodjes en verrukkelijk gebak. Proef het verschil van écht ambacht.</p>
                </div>
                <img class="hero-image" src="img/3d1be192-263a-4423-a654-02a8cdf3fb50.jpeg" alt="Versgebakken ambachtelijke broden">
            </div>
        </section>

        <section class="features-section section-border">
            <div class="container features-grid">
                <?php foreach ($features as $feature): ?>
                    <article class="feature-card">
                        <div class="feature-icon"><?= $feature['icon'] ?></div>
                        <div>
                            <h2><?= htmlspecialchars($feature['title']) ?></h2>
                            <p><?= htmlspecialchars($feature['description']) ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="about-section">
            <div class="container about-grid">
                <div class="about-content">
                    <p class="eyebrow">Over ons</p>
                    <h2>Meer dan brood</h2>
                    <div class="about-text">
                        <p>The Bread Company is ontstaan vanuit een passie voor ambachtelijk brood.</p>
                        <p>Wij geloven in de kracht van pure ingrediënten, tijd en aandacht. Dat proef je in elk hapje.</p>
                    </div>
                    <a href="stat-history.php" class="btn primary icon-btn read-more">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/><path stroke-linecap="round" stroke-linejoin="round" d="m12 5 7 7-7 7"/></svg>
                        Lees ons verhaal
                    </a>
                </div>
                <div class="about-image-wrap">
                    <img class="hero-image" src="img/Mixed-Breads-in-Basket-and-Wooden-Cutting-Board.jpg" alt="Versgebakken ambachtelijke broden">
                    <div class="since-card">
                        <div class="since-icon">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path d="M10 0 3 5v4l7-5 7 5V5l-7-5Zm0 8-7 5v4l7-5 7 5v-4l-7-5Zm0 8-7 5v2h14v-2l-7-5Z"/></svg>
                        </div>
                        <div>
                            <p>Ambacht sinds</p>
                            <strong>2010</strong>
                            <p>Al meer dan 15 jaar bakken wij met liefde en vakmanschap.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
