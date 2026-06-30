<?php
session_start();
$status = $_SESSION['SoortToegang'] ?? 'gast';
$isLoggedIn  = in_array($status, ['Klant', 'Beheer']);
$isBeheerder = $status === 'Beheer';
$naam = $_SESSION['wieBenJeDan'] ?? '';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Bread Company</title>
    <link rel="stylesheet" href="company.css">
    <link rel="stylesheet" href="homepage.css">
</head>
<body>
<?php include "nav.html"; ?>

<?php if (isset($_GET['msg'])): ?>
<div class="hp-msg"><?= htmlspecialchars($_GET['msg']) ?></div>
<?php endif; ?>

<!-- HERO -->
<section class="hp-hero">
    <div class="hp-hero-overlay"></div>
    <div class="hp-hero-content">
        <h1>Vers gebakken,<br>direct bij jou thuis</h1>
        <p>Ambachtelijk brood, knapperige broodjes en luxe gebak — gemaakt met de beste ingrediënten.</p>
        <div class="hp-hero-btns">
            <?php if ($isBeheerder): ?>
                <a href="cli-crud-get.php" class="hp-btn-primary">Klantenbeheer</a>
                <a href="pro-crud-get.php" class="hp-btn-secondary">Productbeheer</a>
            <?php elseif ($isLoggedIn): ?>
                <a href="pur-crud-add.php" class="hp-btn-primary">Bestel nu</a>
                <a href="cli-crud-upd.php" class="hp-btn-secondary">Mijn gegevens</a>
            <?php else: ?>
                <a href="cli-crud-add.php" class="hp-btn-primary">Registreer gratis</a>
                <a href="inlog-client.php" class="hp-btn-secondary">Inloggen</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- WAAROM WIJ -->
<section class="hp-section hp-why">
    <h2>Waarom wij?</h2>
    <div class="hp-why-grid">
        <div class="hp-why-item">
            <div class="hp-why-icon">🥖</div>
            <h3>Ambachtelijke kwaliteit</h3>
            <p>Elk product wordt dagelijks vers gemaakt met zorgvuldig geselecteerde ingrediënten, zonder kunstmatige toevoegingen.</p>
        </div>
        <div class="hp-why-item">
            <div class="hp-why-icon">🚚</div>
            <h3>Snelle bezorging</h3>
            <p>Van onze bakkerij naar jouw deur. Betrouwbare en snelle levering zodat je altijd kunt genieten van versgebakken lekkernijen.</p>
        </div>
        <div class="hp-why-item">
            <div class="hp-why-icon">⭐</div>
            <h3>Meer dan 500 reviews</h3>
            <p>Onze klanten waarderen ons met een gemiddelde van 4,8 sterren. Smaak en service staan bij ons centraal.</p>
        </div>
    </div>
</section>

<!-- HOE WERKT HET -->
<section class="hp-section hp-how" style="background:#f5f3f0;">
    <h2>Hoe werkt het?</h2>
    <div class="hp-how-grid">
        <div class="hp-how-step">
            <div class="hp-how-num">1</div>
            <h3>Kies je producten</h3>
            <p>Blader door ons assortiment en voeg jouw favorieten toe aan je bestelling.</p>
        </div>
        <div class="hp-how-arrow">→</div>
        <div class="hp-how-step">
            <div class="hp-how-num">2</div>
            <h3>Plaats je bestelling</h3>
            <p>Registreer of log in en bevestig je bestelling in een paar klikken.</p>
        </div>
        <div class="hp-how-arrow">→</div>
        <div class="hp-how-step">
            <div class="hp-how-num">3</div>
            <h3>Ontvang & geniet</h3>
            <p>Wij bezorgen vers bij jou thuis. Smakelijk!</p>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="hp-footer">
    <div class="hp-footer-inner">
        <div class="hp-footer-col">
            <h4>Navigatie</h4>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="pro-crud-shw.php">Producten</a></li>
                <?php if ($isLoggedIn && !$isBeheerder): ?>
                <li><a href="pur-crud-shw.php">Mijn bestellingen</a></li>
                <li><a href="cli-crud-upd.php">Mijn gegevens</a></li>
                <?php endif; ?>
                <?php if (!$isLoggedIn): ?>
                <li><a href="inlog-client.php">Inloggen</a></li>
                <li><a href="cli-crud-add.php">Registreren</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="hp-footer-col">
            <h4>Openingstijden</h4>
            <ul class="hp-footer-times">
                <li><span>Maandag – Vrijdag</span><span>07:00 – 18:00</span></li>
                <li><span>Zaterdag</span><span>07:00 – 16:00</span></li>
                <li><span>Zondag</span><span>08:00 – 13:00</span></li>
            </ul>
        </div>
        <div class="hp-footer-col">
            <h4>Volg ons</h4>
            <div class="hp-footer-social">
                <span title="Instagram">📷</span>
                <span title="Facebook">👍</span>
                <span title="Twitter">🐦</span>
            </div>
            <p>Blijf op de hoogte van onze dagelijkse aanbiedingen en nieuwe producten.</p>
        </div>
    </div>
    <div class="hp-footer-copy">
        <p>© 2026 The Bread Company. Alle rechten voorbehouden.</p>
    </div>
</footer>

</body>
</html>
