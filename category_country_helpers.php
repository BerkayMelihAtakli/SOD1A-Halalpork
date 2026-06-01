<?php
require_once 'product_helpers.php';

function clean_name($value) {
    return trim(stripslashes((string)$value));
}

function valid_letters_spaces($value) {
    return preg_match('/^[a-zA-ZÀ-ÿ ]+$/u', $value) === 1;
}

function get_category_by_id($db, $id) {
    $stmt = $db->prepare('SELECT ID, name FROM category WHERE ID = ?');
    $stmt->execute([(int)$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function category_name_exists($db, $name, $excludeId = 0) {
    if ((int)$excludeId > 0) {
        $stmt = $db->prepare('SELECT COUNT(*) FROM category WHERE LOWER(name) = LOWER(?) AND ID <> ?');
        $stmt->execute([$name, (int)$excludeId]);
    } else {
        $stmt = $db->prepare('SELECT COUNT(*) FROM category WHERE LOWER(name) = LOWER(?)');
        $stmt->execute([$name]);
    }
    return (int)$stmt->fetchColumn() > 0;
}

function validate_category_input(&$errors) {
    $name = clean_name($_POST['name'] ?? '');
    if ($name === '' || !valid_letters_spaces($name)) {
        $errors[] = 'Naam is verplicht en mag alleen letters en spaties bevatten.';
    }
    return $name;
}

function category_is_used($db, $id) {
    $stmt = $db->prepare('SELECT COUNT(*) FROM product WHERE categoryid = ?');
    $stmt->execute([(int)$id]);
    return (int)$stmt->fetchColumn() > 0;
}

function get_country_by_id($db, $id) {
    $stmt = $db->prepare('SELECT idcountry, name, code FROM country WHERE idcountry = ?');
    $stmt->execute([(int)$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function country_field_exists($db, $field, $value, $excludeId = 0) {
    $allowed = ['name', 'code'];
    if (!in_array($field, $allowed, true)) {
        return false;
    }
    if ((int)$excludeId > 0) {
        $stmt = $db->prepare("SELECT COUNT(*) FROM country WHERE LOWER($field) = LOWER(?) AND idcountry <> ?");
        $stmt->execute([$value, (int)$excludeId]);
    } else {
        $stmt = $db->prepare("SELECT COUNT(*) FROM country WHERE LOWER($field) = LOWER(?)");
        $stmt->execute([$value]);
    }
    return (int)$stmt->fetchColumn() > 0;
}

function validate_country_input(&$errors) {
    $country = [];
    $country['name'] = clean_name($_POST['name'] ?? '');
    $country['code'] = strtoupper(clean_name($_POST['code'] ?? ''));

    if ($country['name'] === '' || !valid_letters_spaces($country['name'])) {
        $errors[] = 'Landnaam is verplicht en mag alleen letters en spaties bevatten.';
    }
    if ($country['code'] === '' || !valid_letters_spaces($country['code'])) {
        $errors[] = 'Landcode is verplicht en mag alleen letters en spaties bevatten.';
    }
    return $country;
}

function country_is_used_by_product($db, $id) {
    $sql = 'SELECT COUNT(*)
            FROM product p
            INNER JOIN supplier s ON p.supplierid = s.ID
            WHERE s.countryid = ?';
    $stmt = $db->prepare($sql);
    $stmt->execute([(int)$id]);
    return (int)$stmt->fetchColumn() > 0;
}
?>
