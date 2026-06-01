<?php
// Usage: include "inc-header.php";
// Set $pageTitle and $activeNav before including.
$pageTitle  = $pageTitle  ?? 'The Bread Company';
$activeNav  = $activeNav  ?? '';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — The Bread Company</title>
    <link rel="stylesheet" href="style.css">
    <?= $extraStyles ?? '' ?>
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
                    <li class="main-item <?= $activeNav==='home' ? 'active' : '' ?>"><a href="index.php">Home</a></li>
                    <li class="main-item <?= $activeNav==='bedrijf' ? 'active' : '' ?>"><a href="#">Bedrijf</a>
                        <ul>
                            <li><a href="stat-ecofriend.php" class="scndlvl">Eco-vriendelijk</a></li>
                            <li><a href="stat-employees.php" class="scndlvl">Medewerkers</a></li>
                            <li><a href="stat-targets.php" class="scndlvl">Doelstellingen</a></li>
                            <li><a href="stat-history.php" class="scndlvl">Geschiedenis</a></li>
                        </ul>
                    </li>
                    <li class="main-item <?= $activeNav==='assortiment' ? 'active' : '' ?>"><a href="pro-crud-shw.php">Assortiment</a></li>
                    <li class="main-item <?= $activeNav==='onderhoud' ? 'active' : '' ?>"><a href="#">Onderhoud</a>
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
