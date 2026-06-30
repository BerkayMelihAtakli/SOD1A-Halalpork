<?php
session_start();
$status      = $_SESSION['SoortToegang'] ?? '';
$isLoggedIn  = in_array($status, ['Klant', 'Beheer']);
$isBeheerder = $status === 'Beheer';
$klantId     = (int)($_SESSION['welkNummerIsDit'] ?? 0);

require_once 'dbconnect.php';
$stmt = $db->query("
    SELECT p.ID, p.productname, p.price, SUM(pl.quantity) AS total_sold
    FROM product p
    JOIN purchaseline pl ON p.ID = pl.productid
    GROUP BY p.ID, p.productname, p.price
    ORDER BY total_sold DESC
    LIMIT 3
");
$bestsellers = $stmt->fetchAll();

function getBestsellerImage(string $name): string {
    $n = mb_strtolower($name);
    $base = 'https://images.unsplash.com/';
    $suffix = '?w=600&h=400&fit=crop&auto=format';

    if (str_contains($n, 'stroopwafel'))                              return $base.'photo-1611835116500-03c9eb3c7200'.$suffix;
    if (str_contains($n, 'croissant'))                                return $base.'photo-1555507036-ab1f4038808a'.$suffix;
    if (str_contains($n, 'kaneelrol') || str_contains($n, 'kaneel')) return $base.'photo-1694632288834-17d86b340745'.$suffix;
    if (str_contains($n, 'tiramisu'))                                 return $base.'photo-1571877227200-a0d98ea607e9'.$suffix;
    if (str_contains($n, 'chocolade') || str_contains($n, 'choco'))  return $base.'photo-1679812000098-ff557c197028'.$suffix;
    if (str_contains($n, 'ciabatta'))                                 return $base.'photo-1667386773920-c73f3b02a3d6'.$suffix;
    if (str_contains($n, 'emmer') || str_contains($n, 'spelt'))      return $base.'photo-1559811814-e2c57b5e69df'.$suffix;
    if (str_contains($n, 'tijger'))                                   return $base.'photo-1598373182133-52452f7691ef'.$suffix;
    if (str_contains($n, 'casino') || str_contains($n, 'sandwich'))  return $base.'photo-1534620808146-d33bb39128b2'.$suffix;
    if (str_contains($n, 'naan') || str_contains($n, 'turks'))       return $base.'photo-1549413468-cd78edb7e75c'.$suffix;
    if (str_contains($n, 'rogge'))                                    return $base.'photo-1559811814-e2c57b5e69df'.$suffix;

    return $base.'photo-1590301157172-7ba48dd1c2b2'.$suffix;
}
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
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="homepage.css">
</head>
<body>

<?php include 'nav.html'; ?>

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
                <a href="inlog-client.php" class="hp-btn-outline">Inloggen</a>
                <a href="cli-crud-add.php" class="hp-btn-outline">Registreer</a>
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
            <?php foreach ($bestsellers as $p): ?>
            <div class="hp-card">
                <div class="hp-card-img">
                    <img src="<?= getBestsellerImage($p['productname']) ?>" alt="<?= htmlspecialchars($p['productname']) ?>">
                </div>
                <div class="hp-card-body">
                    <div>
                        <h3><?= htmlspecialchars($p['productname']) ?></h3>
                        <p>€ <?= number_format($p['price'], 2, ',', '.') ?></p>
                    </div>
                    <a href="pur-crud-add.php" class="hp-card-btn">Bestel</a>
                </div>
            </div>
            <?php endforeach; ?>
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
