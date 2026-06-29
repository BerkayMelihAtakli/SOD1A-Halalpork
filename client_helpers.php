<?php
require_once 'product_helpers.php';

function is_client() {
    return isset($_SESSION['SoortToegang']) && $_SESSION['SoortToegang'] === 'Klant';
}

function require_client() {
    if (!is_client()) {
        echo '<main><h2>Geen toegang</h2><p>Deze pagina is alleen voor ingelogde klanten.</p>'
           . '<p><a href="login.php">Login als klant</a> | <a href="index.php">Terug naar home</a></p></main>';
        render_footer();
        exit();
    }
}

function fetch_countries($db) {
    $stmt = $db->query('SELECT idcountry, name FROM country ORDER BY name');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function country_select($db, $selectedId = 0) {
    echo '<label>Land</label><select name="country" required>';
    echo '<option value="0">-- kies land --</option>';
    foreach (fetch_countries($db) as $c) {
        $sel = ((int)$c['idcountry'] === (int)$selectedId) ? ' selected' : '';
        echo '<option value="' . h($c['idcountry']) . '"' . $sel . '>' . h($c['name']) . '</option>';
    }
    echo '</select>';
}

function email_exists($db, $email, $excludeId = 0) {
    $stmt = $db->prepare('SELECT COUNT(*) FROM client WHERE email = :email AND id != :id');
    $stmt->execute([':email' => $email, ':id' => $excludeId]);
    return (int)$stmt->fetchColumn() > 0;
}

function validate_client_input(&$errors, $requirePassword = true, $db = null, $excludeId = 0) {
    $client = [];
    $client['first_name'] = trim($_POST['first_name'] ?? '');
    $client['last_name']  = trim($_POST['last_name']  ?? '');
    $client['email']      = trim($_POST['email']      ?? '');
    $client['adress']     = trim($_POST['adress']     ?? '');
    $client['zipcode']    = trim($_POST['zipcode']    ?? '');
    $client['city']       = trim($_POST['city']       ?? '');
    $client['state']      = trim($_POST['state']      ?? '');
    $client['country']    = (int)($_POST['country']   ?? 0);
    $client['telephone']  = trim($_POST['telephone']  ?? '');
    $client['pswrd']      = $_POST['pswrd']           ?? '';

    if ($client['first_name'] === '' || !preg_match('/^[a-zA-ZÀ-ÿ \-]+$/u', $client['first_name'])) {
        $errors[] = 'Voornaam is verplicht en mag alleen letters, spaties en koppeltekens bevatten.';
    }
    if ($client['last_name'] === '' || !preg_match('/^[a-zA-ZÀ-ÿ \-]+$/u', $client['last_name'])) {
        $errors[] = 'Achternaam is verplicht en mag alleen letters, spaties en koppeltekens bevatten.';
    }
    if ($client['email'] === '' || !filter_var($client['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Voer een geldig e-mailadres in.';
    } elseif ($db !== null && email_exists($db, $client['email'], $excludeId)) {
        $errors[] = 'Dit e-mailadres is al in gebruik.';
    }
    if ($client['adress'] === '' || !preg_match('/^[a-zA-ZÀ-ÿ0-9 .\-\/]+$/u', $client['adress'])) {
        $errors[] = 'Adres is verplicht en mag alleen letters, cijfers, spaties en . - / bevatten.';
    }
    if ($client['zipcode'] === '' || !preg_match('/^[a-zA-Z0-9 \-]+$/', $client['zipcode'])) {
        $errors[] = 'Postcode is verplicht.';
    }
    if ($client['city'] === '' || !preg_match('/^[a-zA-ZÀ-ÿ \-]+$/u', $client['city'])) {
        $errors[] = 'Woonplaats is verplicht en mag alleen letters bevatten.';
    }
    if ($client['state'] !== '' && !preg_match('/^[a-zA-ZÀ-ÿ \-]+$/u', $client['state'])) {
        $errors[] = 'Provincie/staat mag alleen letters en spaties bevatten.';
    }
    if ($client['country'] <= 0) {
        $errors[] = 'Kies een land.';
    }
    if ($client['telephone'] !== '' && !preg_match('/^[0-9 \+\-\(\)]+$/', $client['telephone'])) {
        $errors[] = 'Telefoonnummer mag alleen cijfers, spaties en + - ( ) bevatten.';
    }
    if ($requirePassword) {
        if (strlen($client['pswrd']) < 6) {
            $errors[] = 'Wachtwoord is verplicht en moet minimaal 6 tekens bevatten.';
        }
        $confirm = $_POST['pswrd_confirm'] ?? '';
        if ($client['pswrd'] !== $confirm) {
            $errors[] = 'De twee wachtwoorden komen niet overeen.';
        }
    }
    return $client;
}

function client_form_fields($db, $data = [], $showPassword = false) {
    $fn  = $data['first_name'] ?? '';
    $ln  = $data['last_name']  ?? '';
    $em  = $data['email']      ?? '';
    $adr = $data['adress']     ?? '';
    $zip = $data['zipcode']    ?? '';
    $cit = $data['city']       ?? '';
    $sta = $data['state']      ?? '';
    $tel = $data['telephone']  ?? '';
    $cou = $data['country']    ?? 0;

    echo '<label>Voornaam</label><input type="text" name="first_name" value="' . h($fn) . '" required>';
    echo '<label>Achternaam</label><input type="text" name="last_name" value="' . h($ln) . '" required>';
    echo '<label>E-mail</label><input type="email" name="email" value="' . h($em) . '" required>';
    echo '<label>Adres</label><input type="text" name="adress" value="' . h($adr) . '" required>';
    echo '<label>Postcode</label><input type="text" name="zipcode" value="' . h($zip) . '" required>';
    echo '<label>Woonplaats</label><input type="text" name="city" value="' . h($cit) . '" required>';
    echo '<label>Provincie/staat</label><input type="text" name="state" value="' . h($sta) . '">';
    country_select($db, $cou);
    echo '<label>Telefoonnummer</label><input type="text" name="telephone" value="' . h($tel) . '">';
    if ($showPassword) {
        echo '<label>Wachtwoord</label><input type="password" name="pswrd" placeholder="Minimaal 6 tekens">';
        echo '<label>Wachtwoord (herhalen)</label><input type="password" name="pswrd_confirm" placeholder="Herhaal wachtwoord">';
    }
}
?>
