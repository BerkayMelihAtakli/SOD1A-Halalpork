<?php
session_start();
require_once 'product_helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uitloggen — The Bread Company</title>
    <link rel="stylesheet" href="company.css">
</head>
<body class="hp-page">

<?php include 'nav.html'; ?>

<div class="logout-wrap">
    <div class="logout-card">
        <div class="logout-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#7d5ba6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
        </div>
        <h1>Uitloggen</h1>
        <p>Weet je zeker dat je wilt uitloggen?</p>
        <div class="logout-btns">
            <form action="logout.php" method="post" style="flex:1; display:contents;">
                <input type="hidden" name="confirm_logout" value="1">
                <button type="submit" class="logout-btn-confirm">Uitloggen</button>
            </form>
            <a href="index.php" class="logout-btn-cancel">Annuleren</a>
        </div>
    </div>
</div>

</body>
</html>
