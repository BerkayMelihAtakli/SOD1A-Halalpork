<?php
require_once 'product_helpers.php';

function fetch_countries($db) {
    $stmt = $db->query('SELECT idcountry, name FROM country ORDER BY name');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function country_select($db, $selectedId = 0) {
    echo '<label>Land</label><select name="country">';
    echo '<option value="0">-- kies land --</option>';
    foreach (fetch_countries($db) as $c) {
        $sel = ((int)$c['idcountry'] === (int)$selectedId) ? ' selected' : '';
        echo '<option value="' . h($c['idcountry']) . '"' . $sel . '>' . h($c['name']) . '</option>';
    }
    echo '</select>';
}

function validate_client_input(&$errors, $requirePassword = true) {
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
    }
    if ($requirePassword) {
        if (strlen($client['pswrd']) < 6) {
            $errors[] = 'Wachtwoord is verplicht en moet minimaal 6 tekens bevatten.';
        }
    }
    return $client;
}

function client_form_fields($db, $data = [], $showPassword = true) {
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
    echo '<label>Adres</label><input type="text" name="adress" value="' . h($adr) . '">';
    echo '<label>Postcode</label><input type="text" name="zipcode" value="' . h($zip) . '">';
    echo '<label>Woonplaats</label><input type="text" name="city" value="' . h($cit) . '">';
    echo '<label>Provincie/staat</label><input type="text" name="state" value="' . h($sta) . '">';
    country_select($db, $cou);
    echo '<label>Telefoonnummer</label><input type="text" name="telephone" value="' . h($tel) . '">';
    if ($showPassword) {
        echo '<label>Nieuw wachtwoord</label><input type="password" name="pswrd" placeholder="Laat leeg om niet te wijzigen">';
    }
}
?>
