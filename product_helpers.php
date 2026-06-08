<?php
function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function is_admin() {
    return isset($_SESSION['SoortToegang']) && $_SESSION['SoortToegang'] === 'Beheer';
}

function require_admin() {
    if (!is_admin()) {
        echo '<main><h2>Geen toegang</h2><p>Deze pagina is alleen voor ingelogde beheerders.</p><p><a href="login.php">Login als beheerder</a> | <a href="index.php">Terug naar home</a></p></main>';
        exit();
    }
}


function is_client() {
    return isset($_SESSION['SoortToegang']) && $_SESSION['SoortToegang'] === 'Klant';
}

function require_client() {
    if (!is_client() || !isset($_SESSION['welkNummerIsDit']) || (int)$_SESSION['welkNummerIsDit'] <= 0) {
        echo '<main><h2>Geen toegang</h2><p>Deze pagina is alleen voor ingelogde klanten.</p><p><a href="login.php">Login als klant</a> | <a href="index.php">Terug naar home</a></p></main>';
        exit();
    }
}

function active_text($value) {
    if ($value === 'J') {
        return 'Actief';
    }
    return 'Inactief';
}

function render_header($title) {
    echo '<!DOCTYPE html><html lang="nl"><head><meta charset="UTF-8"><title>' . h($title) . '</title><link rel="stylesheet" type="text/css" href="company.css"></head><body>';
    include 'nav.html';
}

function render_footer() {
    echo '</body></html>';
}

function clean_text($value) {
    return trim(stripslashes((string)$value));
}

function validate_product_input(&$errors) {
    $product = [];
    $product['productname'] = clean_text($_POST['productname'] ?? '');
    $product['ingredients'] = clean_text($_POST['ingredients'] ?? '');
    $product['allergens'] = clean_text($_POST['allergens'] ?? '');
    $priceRaw = clean_text($_POST['price'] ?? '');
    $product['categoryid'] = (int)($_POST['categoryid'] ?? 0);
    $product['supplierid'] = (int)($_POST['supplierid'] ?? 0);

    if ($product['productname'] === '' || !preg_match('/^[a-zA-ZÀ-ÿ ]+$/u', $product['productname'])) {
        $errors[] = 'Productnaam is verplicht en mag alleen letters en spaties bevatten.';
    }
    if ($product['ingredients'] !== '' && !preg_match('/^[a-zA-ZÀ-ÿ0-9 ,._()\-]+$/u', $product['ingredients'])) {
        $errors[] = 'Ingrediënten mogen alleen letters, cijfers, spaties en simpele leestekens bevatten.';
    }
    if ($product['allergens'] !== '' && !preg_match('/^[a-zA-ZÀ-ÿ0-9 ,._()\-]+$/u', $product['allergens'])) {
        $errors[] = 'Allergenen mogen alleen letters, cijfers, spaties en simpele leestekens bevatten.';
    }
    if ($priceRaw === '' || !preg_match('/^[0-9]{1,2},[0-9]{2}$/', $priceRaw)) {
        $errors[] = 'Prijs is verplicht en moet het format 12,34 hebben.';
    }
    $product['price'] = str_replace(',', '.', $priceRaw);
    if ($product['categoryid'] <= 0) {
        $errors[] = 'Kies een categorie.';
    }
    if ($product['supplierid'] <= 0) {
        $errors[] = 'Kies een leverancier.';
    }
    return $product;
}

function price_to_form($price) {
    return number_format((float)$price, 2, ',', '');
}

function fetch_categories($db) {
    $stmt = $db->query('SELECT ID, name FROM category ORDER BY name');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetch_suppliers($db) {
    $stmt = $db->query('SELECT ID, company FROM supplier ORDER BY company');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function product_selects($db, $selectedCategory = 0, $selectedSupplier = 0) {
    echo '<label>Categorie</label><select name="categoryid" required>';
    echo '<option value="">-- kies categorie --</option>';
    foreach (fetch_categories($db) as $cat) {
        $sel = ((int)$cat['ID'] === (int)$selectedCategory) ? ' selected' : '';
        echo '<option value="' . h($cat['ID']) . '"' . $sel . '>' . h($cat['name']) . '</option>';
    }
    echo '</select>';

    echo '<label>Leverancier</label><select name="supplierid" required>';
    echo '<option value="">-- kies leverancier --</option>';
    foreach (fetch_suppliers($db) as $sup) {
        $sel = ((int)$sup['ID'] === (int)$selectedSupplier) ? ' selected' : '';
        echo '<option value="' . h($sup['ID']) . '"' . $sel . '>' . h($sup['company']) . '</option>';
    }
    echo '</select>';
}

function product_form_fields($db, $product = []) {
    $productname = $product['productname'] ?? '';
    $ingredients = $product['ingredients'] ?? '';
    $allergens = $product['allergens'] ?? '';
    $price = isset($product['price']) ? price_to_form($product['price']) : '';
    $categoryid = $product['categoryid'] ?? 0;
    $supplierid = $product['supplierid'] ?? 0;

    echo '<label>Productnaam</label><input type="text" name="productname" value="' . h($productname) . '" required pattern="[A-Za-zÀ-ÿ ]+">';
    echo '<label>Ingrediënten</label><input type="text" name="ingredients" value="' . h($ingredients) . '">';
    echo '<label>Allergenen</label><input type="text" name="allergens" value="' . h($allergens) . '">';
    echo '<label>Prijs</label><input type="text" name="price" value="' . h($price) . '" required placeholder="12,34" pattern="[0-9]{1,2},[0-9]{2}">';
    product_selects($db, $categoryid, $supplierid);
}
?>
