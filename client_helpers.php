<?php
// Retorna true se o utilizador tem sessao de klant
function is_client() {
    return isset($_SESSION['SoortToegang']) && $_SESSION['SoortToegang'] === 'Klant';
}

// Toont alle invoervelden voor een klant; $showPassword = true bij registreren/wijzigen
function client_form_fields($db, $old = [], $showPassword = false) {
    $fn        = $old['first_name'] ?? '';
    $ln        = $old['last_name']  ?? '';
    $email     = $old['email']      ?? '';
    $adress    = $old['adress']     ?? '';
    $zipcode   = $old['zipcode']    ?? '';
    $city      = $old['city']       ?? '';
    $state     = $old['state']      ?? '';
    $telephone = $old['telephone']  ?? '';
    $countryId = $old['country']    ?? 0;

    echo '<label>Voornaam</label>';
    echo '<input type="text" name="first_name" value="' . h($fn) . '" required>';
    echo '<label>Achternaam</label>';
    echo '<input type="text" name="last_name" value="' . h($ln) . '" required>';
    echo '<label>E-mailadres</label>';
    echo '<input type="email" name="email" value="' . h($email) . '" required>';
    echo '<label>Adres</label>';
    echo '<input type="text" name="adress" value="' . h($adress) . '" required>';
    echo '<label>Postcode</label>';
    echo '<input type="text" name="zipcode" value="' . h($zipcode) . '" required>';
    echo '<label>Woonplaats</label>';
    echo '<input type="text" name="city" value="' . h($city) . '" required>';
    echo '<label>Provincie/staat</label>';
    echo '<input type="text" name="state" value="' . h($state) . '">';

    // Landen uit de database
    $stmt = $db->query('SELECT idcountry, name FROM country ORDER BY name');
    $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '<label>Land</label>';
    echo '<select name="country" required><option value="">-- kies land --</option>';
    foreach ($countries as $c) {
        $sel = ((int)$c['idcountry'] === (int)$countryId) ? ' selected' : '';
        echo '<option value="' . h($c['idcountry']) . '"' . $sel . '>' . h($c['name']) . '</option>';
    }
    echo '</select>';

    echo '<label>Telefoonnummer</label>';
    echo '<input type="text" name="telephone" value="' . h($telephone) . '">';

    if ($showPassword) {
        echo '<label>Wachtwoord</label>';
        echo '<input type="password" name="pswrd" autocomplete="new-password">';
        echo '<label>Herhaal wachtwoord</label>';
        echo '<input type="password" name="pswrd2" autocomplete="new-password">';
    }
}

// Valideert POST-gegevens voor client; $requirePassword = true bij registreren
// $db + $excludeId worden gebruikt voor unieke e-mailcheck
function validate_client_input(&$errors, $requirePassword, $db = null, $excludeId = null) {
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
    $client['pswrd']      = $_POST['pswrd']  ?? '';
    $pswrd2               = $_POST['pswrd2'] ?? '';

    // Verplichte velden
    if ($client['first_name'] === '' || !preg_match('/^[A-Za-z\xC0-\xFF ]+$/u', $client['first_name']))
        $errors[] = 'Voornaam is verplicht en mag alleen letters en spaties bevatten.';
    if ($client['last_name'] === '' || !preg_match('/^[A-Za-z\xC0-\xFF ]+$/u', $client['last_name']))
        $errors[] = 'Achternaam is verplicht en mag alleen letters en spaties bevatten.';

    // E-mail validatie + uniciteitscheck
    if (!filter_var($client['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Vul een geldig e-mailadres in.';
    } elseif ($db !== null) {
        $q = 'SELECT COUNT(*) FROM client WHERE email = :email' .
             ($excludeId !== null ? ' AND id <> :excl' : '');
        $p = $db->prepare($q);
        $p->bindValue(':email', $client['email']);
        if ($excludeId !== null) $p->bindValue(':excl', (int)$excludeId, PDO::PARAM_INT);
        $p->execute();
        if ((int)$p->fetchColumn() > 0) $errors[] = 'Dit e-mailadres is al in gebruik.';
    }

    if ($client['adress'] === '' || !preg_match('/^[A-Za-z\xC0-\xFF0-9 ]+$/u', $client['adress']))
        $errors[] = 'Adres is verplicht en mag alleen letters, cijfers en spaties bevatten.';
    if ($client['zipcode'] === '' || !preg_match('/^[A-Za-z0-9 ]+$/', $client['zipcode']))
        $errors[] = 'Postcode is verplicht en mag alleen letters, cijfers en spaties bevatten.';
    if ($client['city'] === '' || !preg_match('/^[A-Za-z\xC0-\xFF ]+$/u', $client['city']))
        $errors[] = 'Woonplaats is verplicht en mag alleen letters en spaties bevatten.';

    // Optionele velden
    if ($client['state'] !== '' && !preg_match('/^[A-Za-z\xC0-\xFF ]+$/u', $client['state']))
        $errors[] = 'Provincie/staat mag alleen letters en spaties bevatten.';
    if ($client['country'] <= 0)
        $errors[] = 'Kies een land.';
    if ($client['telephone'] !== '' && !preg_match('/^[0-9 ]+$/', $client['telephone']))
        $errors[] = 'Telefoonnummer mag alleen cijfers en spaties bevatten.';

    // Wachtwoord
    if ($requirePassword) {
        if ($client['pswrd'] === '') $errors[] = 'Wachtwoord is verplicht.';
        elseif ($client['pswrd'] !== $pswrd2) $errors[] = 'De twee wachtwoorden komen niet overeen.';
    } else {
        // Bij wijzigen: wachtwoord optioneel, maar als ingevuld moeten beide overeenkomen
        if ($client['pswrd'] !== '' && $client['pswrd'] !== $pswrd2)
            $errors[] = 'De twee wachtwoorden komen niet overeen.';
    }

    return $client;
}
?>
