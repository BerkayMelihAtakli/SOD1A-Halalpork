<?php
require_once "dbconnect.php";

// Employees = clients with admin rights — all available fields from DB
$stmtEmployees = $db->query("
    SELECT c.id, c.first_name, c.last_name, c.email,
           c.telephone, c.adress, c.zipcode, c.city, c.state, co.name AS country
    FROM client c
    LEFT JOIN country co ON c.country = co.idcountry
    WHERE c.isadmin = 'J'
    ORDER BY c.last_name, c.first_name
");
$employees = $stmtEmployees->fetchAll(PDO::FETCH_ASSOC);

// Stats from the database
$stats = $db->query("
    SELECT
        (SELECT COUNT(*) FROM client WHERE isadmin = 'J') AS total_employees,
        (SELECT COUNT(*) FROM client WHERE isadmin = 'N') AS total_clients,
        (SELECT COUNT(*) FROM purchase)                   AS total_purchases,
        (SELECT COUNT(*) FROM product WHERE isactive = 'J') AS total_products,
        (SELECT COUNT(*) FROM supplier)                   AS total_suppliers
")->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medewerkers — The Bread Company</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* ── Page hero ── */
        .page-hero { border-bottom: 1px solid #ded6cc; }

        .page-hero-inner {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding-top: 48px;
            padding-bottom: 48px;
        }

        .page-hero-inner h1 { font-size: 48px; line-height: 1; max-width: 640px; margin-top: 12px; }

        .page-hero-inner .intro-text { max-width: 560px; }

        /* ── Stats strip ── */
        .stats-strip { background: #f5f1eb; border-bottom: 1px solid #ded6cc; }

        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 28px 16px;
            border-left: 1px solid #ded6cc;
            text-align: center;
        }
        .stat-item:first-child { border-left: 0; }

        .stat-number {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 40px;
            font-weight: 700;
            line-height: 1;
            color: #d89a18;
        }
        .stat-label { font-size: 12px; color: #6c5f53; }

        /* ── Employee table section ── */
        .employees-section { border-bottom: 1px solid #ded6cc; }

        .employees-inner { padding-top: 52px; padding-bottom: 52px; }

        .section-header { margin-bottom: 32px; }
        .section-header h2 { font-size: 38px; margin-top: 10px; }

        .emp-table-wrap {
            border: 1px solid #ded6cc;
            border-radius: 10px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead { background: #f5f1eb; }

        th {
            padding: 12px 16px;
            text-align: left;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #54463b;
            border-bottom: 1px solid #ded6cc;
            white-space: nowrap;
        }

        td {
            padding: 13px 16px;
            border-bottom: 1px solid #ede8e2;
            color: #2b1b10;
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: 0; }

        tbody tr:hover td { background: #faf7f3; }

        .emp-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f7efe0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #d89a18;
            flex-shrink: 0;
        }

        .emp-avatar svg { width: 16px; height: 16px; }

        .name-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .emp-name { font-weight: 600; }
        .emp-sub { font-size: 11px; color: #6c5f53; margin-top: 2px; }

        .contact-cell { display: flex; flex-direction: column; gap: 2px; }
        .contact-phone { font-size: 13px; color: #2b1b10; }
        .contact-email { font-size: 11px; color: #6c5f53; }

        .addr-cell { display: flex; flex-direction: column; gap: 2px; font-size: 13px; }
        .addr-street { color: #2b1b10; }
        .addr-city { font-size: 11px; color: #6c5f53; }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            background: #f7efe0;
            color: #c88c16;
            border: 1px solid #e8d09a;
        }

        .empty-state {
            padding: 40px;
            text-align: center;
            color: #6c5f53;
            font-size: 14px;
        }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            .page-hero-inner h1 { font-size: 34px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .stat-item:nth-child(3) { border-left: 0; }
            .col-addr, .col-country { display: none; }
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
                <div class="topbar-item">
                    <div class="phone hide-mobile">
                        <svg class="small-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79a15.054 15.054 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1-.24c1.12.37 2.33.57 3.59.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.06 21 3 13.94 3 5a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.26.2 2.47.57 3.59a1 1 0 0 1-.24 1l-2.2 2.2Z"/></svg>
                        <span>Bel ons: 085 123 4567</span>
                    </div>
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

                        <li class="main-item active"><a href="#">Bedrijf</a>
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
                <p class="eyebrow">Ons team</p>
                <h1>Medewerkers van The Bread Company</h1>
                <p class="intro-text">
                    Hieronder vindt u een overzicht van alle medewerkers met beheerdersrechten binnen ons systeem,
                    samen met actuele cijfers over het bedrijf.
                </p>
            </div>
        </section>

        <section class="stats-strip section-border">
            <div class="container stats-grid">
                <div class="stat-item">
                    <span class="stat-number"><?= (int)$stats['total_employees'] ?></span>
                    <span class="stat-label">Medewerkers</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= (int)$stats['total_clients'] ?></span>
                    <span class="stat-label">Klanten bediend</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= (int)$stats['total_purchases'] ?></span>
                    <span class="stat-label">Bestellingen verwerkt</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= (int)$stats['total_products'] ?></span>
                    <span class="stat-label">Actieve producten</span>
                </div>
            </div>
        </section>

        <section class="employees-section">
            <div class="container employees-inner">
                <div class="section-header">
                    <p class="eyebrow">Beheerders</p>
                    <h2>Teamoverzicht</h2>
                </div>

                <div class="emp-table-wrap">
                    <?php if (empty($employees)): ?>
                        <p class="empty-state">Geen medewerkers gevonden in de database.</p>
                    <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Naam</th>
                                <th>Contact</th>
                                <th class="col-addr">Adres</th>
                                <th class="col-addr">Provincie / Land</th>
                                <th>Rol</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($employees as $emp): ?>
                            <tr>
                                <td>
                                    <div class="name-cell">
                                        <span class="emp-avatar">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <circle cx="12" cy="8" r="4"/>
                                                <path stroke-linecap="round" d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="emp-name">
                                                <?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?>
                                            </div>
                                            <div class="emp-sub">#<?= (int)$emp['id'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="contact-cell">
                                        <span class="contact-phone"><?= htmlspecialchars($emp['telephone'] ?? '—') ?></span>
                                        <span class="contact-email"><?= htmlspecialchars($emp['email'] ?? '—') ?></span>
                                    </div>
                                </td>
                                <td class="col-addr">
                                    <div class="addr-cell">
                                        <span class="addr-street">
                                            <?= htmlspecialchars(trim(($emp['adress'] ?? '') . ' ' . ($emp['zipcode'] ?? ''))) ?: '—' ?>
                                        </span>
                                        <span class="addr-city"><?= htmlspecialchars($emp['city'] ?? '—') ?></span>
                                    </div>
                                </td>
                                <td class="col-addr">
                                    <div class="addr-cell">
                                        <span class="addr-street"><?= htmlspecialchars($emp['state'] ?? '—') ?></span>
                                        <span class="addr-city"><?= htmlspecialchars($emp['country'] ?? '—') ?></span>
                                    </div>
                                </td>
                                <td><span class="badge">Beheerder</span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>

            </div>
        </section>

    </main>

</div>
</body>
</html>
