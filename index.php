<?php
session_start();
$status      = $_SESSION['SoortToegang'] ?? '';
$isLoggedIn  = in_array($status, ['Klant', 'Beheer']);
$isBeheerder = $status === 'Beheer';
$klantId     = (int)($_SESSION['welkNummerIsDit'] ?? 0);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Bread Company</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="homepage.css">
</head>
<body>

<!-- ── NAV ─────────────────────────────────────────────────────── -->
<nav class="hp-nav">
  <div class="hp-nav-inner">
    <a href="index.php" class="hp-logo">
      <span class="hp-logo-a">Halal</span><span class="hp-logo-b">Pork</span>
    </a>
    <div class="hp-nav-links">
      <a href="index.php" class="hp-link">Home</a>

      <?php if ($isBeheerder): ?>
        <div class="hp-drop-wrap">
          <button class="hp-link hp-drop-btn">Overzicht <svg class="hp-chev" viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
          <div class="hp-drop">
            <a href="cat-crud-shw.php">Categorieën</a>
            <a href="cli-crud-shw.php">Klanten</a>
            <a href="sup-crud-shw.php">Leveranciers</a>
            <a href="pro-crud-shw.php">Producten</a>
            <a href="pur-crud-shw.php">Aankopen</a>
            <a href="cou-crud-shw.php">Landen</a>
          </div>
        </div>
        <div class="hp-drop-wrap">
          <button class="hp-link hp-drop-btn">Bedrijf <svg class="hp-chev" viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
          <div class="hp-drop">
            <a href="stat-ecofriend.php">Eco-vriendelijk</a>
            <a href="stat-employees.php">Medewerkers</a>
            <a href="stat-targets.php">Doelstellingen</a>
            <a href="stat-history.php">Geschiedenis</a>
          </div>
        </div>
        <div class="hp-drop-wrap">
          <button class="hp-link hp-drop-btn">Informatie <svg class="hp-chev" viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
          <div class="hp-drop">
            <a href="cli-shw-pur.php">Klanten + aankopen</a>
            <a href="cli-shw-nopur.php">Klanten zonder aankoop</a>
            <a href="cli-shw-admin.php">Alle beheerders</a>
            <a href="avg-prodprice-per-supl.php">Prodprijs per lev</a>
            <a href="cat-shw-avgprice.php">Prodprijs per catgr</a>
            <a href="shw-prod-per-supl.php">Produkt per lev</a>
            <a href="shw-prod-per-cat.php">Produkt per cat</a>
          </div>
        </div>
        <div class="hp-drop-wrap">
          <button class="hp-link hp-drop-btn">Onderhoud <svg class="hp-chev" viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
          <div class="hp-drop">
            <a href="cat-crud-get.php">Categorieen</a>
            <a href="cli-crud-get.php">Klanten</a>
            <a href="sup-crud-get.php">Leveranciers</a>
            <a href="pro-crud-get.php">Producten</a>
            <a href="pur-crud-get.php">Aankopen</a>
            <a href="cou-crud-get.php">Landen</a>
            <a href="admin-add.php">Beheerrechten geven</a>
          </div>
        </div>
        <div class="hp-drop-wrap">
          <button class="hp-link hp-drop-btn">Mijn Account <svg class="hp-chev" viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
          <div class="hp-drop">
            <span class="hp-drop-label">Beheerder</span>
            <a href="logout.php">Uitloggen</a>
          </div>
        </div>

      <?php elseif ($isLoggedIn): ?>
        <div class="hp-drop-wrap">
          <button class="hp-link hp-drop-btn">Overzicht <svg class="hp-chev" viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
          <div class="hp-drop">
            <a href="cat-crud-shw.php">Categorieën</a>
            <a href="pro-crud-shw.php">Producten</a>
          </div>
        </div>
        <div class="hp-drop-wrap">
          <button class="hp-link hp-drop-btn">Bedrijf <svg class="hp-chev" viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
          <div class="hp-drop">
            <a href="stat-ecofriend.php">Eco-vriendelijk</a>
            <a href="stat-employees.php">Medewerkers</a>
            <a href="stat-targets.php">Doelstellingen</a>
            <a href="stat-history.php">Geschiedenis</a>
          </div>
        </div>
        <div class="hp-drop-wrap">
          <button class="hp-link hp-drop-btn">Mijn Account <svg class="hp-chev" viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
          <div class="hp-drop">
            <a href="pur-crud-shw.php">Mijn bestellingen</a>
            <a href="pur-crud-add.php">Nieuwe bestelling</a>
            <a href="cli-crud-upd.php?id=<?= $klantId ?>">Mijn gegevens</a>
            <a href="logout.php">Uitloggen</a>
          </div>
        </div>

      <?php else: ?>
        <div class="hp-drop-wrap">
          <button class="hp-link hp-drop-btn">Overzicht <svg class="hp-chev" viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
          <div class="hp-drop">
            <a href="cat-crud-shw.php">Categorieën</a>
            <a href="pro-crud-shw.php">Producten</a>
          </div>
        </div>
        <div class="hp-drop-wrap">
          <button class="hp-link hp-drop-btn">Bedrijf <svg class="hp-chev" viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
          <div class="hp-drop">
            <a href="stat-ecofriend.php">Eco-vriendelijk</a>
            <a href="stat-employees.php">Medewerkers</a>
            <a href="stat-targets.php">Doelstellingen</a>
            <a href="stat-history.php">Geschiedenis</a>
          </div>
        </div>
        <a href="inlog-client.php" class="hp-link">Inloggen</a>
        <a href="inlog-admin.php" class="hp-link">Beheerder</a>
        <a href="cli-crud-add.php" class="hp-cta">Registreer gratis</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<?php if (isset($_GET['msg'])): ?>
<div class="hp-msg"><?= htmlspecialchars($_GET['msg']) ?></div>
<?php endif; ?>

<!-- ── HERO ─────────────────────────────────────────────────────── -->
<section class="hp-hero">
    <img src="https://images.unsplash.com/photo-1725297952102-ab28892a31ab?w=1600&h=600&fit=crop&auto=format" alt="Vers artisanaal brood" class="hp-hero-img">
    <div class="hp-hero-overlay"></div>
    <div class="hp-hero-content">
        <h1>Vers gebakken,<br>direct bij jou thuis</h1>
        <p>Ontdek de heerlijke wereld van versgebakken brood, knapperige broodjes en verrukkelijk gebak — allemaal binnen handbereik. Met de beste ingrediënten, zonder kunstmatige toevoegingen.</p>
        <div class="hp-hero-btns">
            <?php if ($isBeheerder): ?>
                <a href="cli-crud-get.php" class="hp-btn-primary">Klantenbeheer</a>
                <a href="pro-crud-get.php" class="hp-btn-outline">Productbeheer</a>
            <?php elseif ($isLoggedIn): ?>
                <a href="pur-crud-add.php" class="hp-btn-primary">Bestel nu</a>
                <a href="cli-crud-upd.php?id=<?= $klantId ?>" class="hp-btn-outline">Mijn gegevens</a>
            <?php else: ?>
                <a href="pur-crud-add.php" class="hp-btn-primary">Bestel nu</a>
                <a href="cli-crud-add.php" class="hp-btn-outline">Registreer gratis</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ── WAAROM WIJ ─────────────────────────────────────────────── -->
<section class="hp-why">
    <div class="hp-why-inner">
        <h2 class="hp-why-title">Waarom wij?</h2>
        <div class="hp-why-grid">
            <div class="hp-why-item">
                <div class="hp-why-icon-wrap">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7d5ba6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg>
                </div>
                <div>
                    <h3>Puur vakmanschap</h3>
                    <p>Van ambachtelijk zuurdesembrood tot luxe taarten — gemaakt met passie voor kwaliteit en smaak, zonder kunstmatige toevoegingen.</p>
                </div>
            </div>
            <div class="hp-why-item">
                <div class="hp-why-icon-wrap">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7d5ba6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v4h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <div>
                    <h3>Bestel vanuit je luie stoel</h3>
                    <p>Met slechts een paar klikken heb je jouw favorieten thuis bezorgd. Snel, betrouwbaar en altijd vers.</p>
                </div>
            </div>
            <div class="hp-why-item">
                <div class="hp-why-icon-wrap">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7d5ba6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                <div>
                    <h3>Een onvergetelijke smaak</h3>
                    <p>Wij beloven je een onvergetelijke smaakervaring en een glimlach op je gezicht, elke bestelling opnieuw.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── BESTSELLERS ────────────────────────────────────────────── -->
<section class="hp-best">
    <div class="hp-best-inner">
        <h2>Bestsellers</h2>
        <div class="hp-best-grid">
            <div class="hp-card">
                <div class="hp-card-img">
                    <img src="https://images.unsplash.com/photo-1590301157172-7ba48dd1c2b2?w=600&h=400&fit=crop&auto=format" alt="Zuurdesem brood op houten tafel">
                </div>
                <div class="hp-card-body">
                    <div>
                        <h3>Zuurdesembrood</h3>
                        <p>€ 6,50</p>
                    </div>
                    <a href="pur-crud-add.php" class="hp-card-btn">Bestel</a>
                </div>
            </div>
            <div class="hp-card">
                <div class="hp-card-img">
                    <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=600&h=400&fit=crop&auto=format" alt="Versgebakken croissants">
                </div>
                <div class="hp-card-body">
                    <div>
                        <h3>Croissant</h3>
                        <p>€ 2,95</p>
                    </div>
                    <a href="pur-crud-add.php" class="hp-card-btn">Bestel</a>
                </div>
            </div>
            <div class="hp-card">
                <div class="hp-card-img">
                    <img src="https://images.unsplash.com/photo-1694632288834-17d86b340745?w=600&h=400&fit=crop&auto=format" alt="Kaneelrollen met glazuur">
                </div>
                <div class="hp-card-body">
                    <div>
                        <h3>Kaneelrol</h3>
                        <p>€ 3,75</p>
                    </div>
                    <a href="pur-crud-add.php" class="hp-card-btn">Bestel</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── HOE WERKT HET ──────────────────────────────────────────── -->
<section class="hp-how">
    <div class="hp-how-inner">
        <h2>Hoe werkt het?</h2>
        <div class="hp-how-grid">
            <div class="hp-how-line"></div>
            <div class="hp-step">
                <div class="hp-step-num hp-step-light">01</div>
                <h3>Kies je product</h3>
                <p>Blader door ons uitgebreide assortiment en kies jouw favoriete brood, broodjes of gebak.</p>
            </div>
            <div class="hp-step">
                <div class="hp-step-num hp-step-dark">02</div>
                <h3>Kies de hoeveelheid</h3>
                <p>Geef aan hoeveel je wilt bestellen — of het nu één brood is of een heel feestassortiment.</p>
            </div>
            <div class="hp-step">
                <div class="hp-step-num hp-step-light">03</div>
                <h3>Bestel &amp; geniet</h3>
                <p>Rond je bestelling af en laat je verwennen door de smaak van puur vakmanschap, thuis bezorgd.</p>
            </div>
        </div>
    </div>
</section>

<!-- ── FOOTER ─────────────────────────────────────────────────── -->
<footer class="hp-footer">
    <div class="hp-footer-inner">
        <div class="hp-footer-col">
            <h4>Navigatie</h4>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="pro-crud-shw.php">Producten</a></li>
                <li><a href="stat-history.php">Over ons</a></li>
                <?php if ($isLoggedIn): ?>
                <li><a href="pur-crud-shw.php">Mijn bestellingen</a></li>
                <?php endif; ?>
                <li><a href="inlog-client.php">Contact</a></li>
            </ul>
        </div>
        <div class="hp-footer-col">
            <h4>Openingstijden</h4>
            <ul class="hp-times">
                <li><span>Maandag – Vrijdag</span><span>07:00 – 18:00</span></li>
                <li><span>Zaterdag</span><span>07:00 – 16:00</span></li>
                <li><span>Zondag</span><span>08:00 – 13:00</span></li>
            </ul>
        </div>
        <div class="hp-footer-col">
            <h4>Volg ons</h4>
            <div class="hp-social">
                <button class="hp-social-btn" aria-label="Instagram">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                </button>
                <button class="hp-social-btn" aria-label="Facebook">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                </button>
                <button class="hp-social-btn" aria-label="Twitter">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg>
                </button>
            </div>
            <p>Blijf op de hoogte van onze dagelijkse aanbiedingen en nieuwe producten.</p>
        </div>
    </div>
    <div class="hp-footer-copy">
        <p>© 2026 De Bakkerij. Alle rechten voorbehouden.</p>
    </div>
</footer>

</body>
</html>
