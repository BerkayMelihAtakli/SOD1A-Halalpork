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

function render_header($title) {
    echo '<!DOCTYPE html><html lang="nl"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>' . h($title) . '</title><link rel="stylesheet" href="company.css"></head><body>';
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

function getProductImage(string $name, int $categoryId = 0): string {
    $n = mb_strtolower($name);
    $b = 'https://images.unsplash.com/';
    $q = '?w=600&h=400&fit=crop&auto=format';

    if (str_contains($n, 'stroopwafel') || str_contains($n, 'wafel'))         return $b.'photo-1611835116500-03c9eb3c7200'.$q;
    if (str_contains($n, 'croissant'))                                         return $b.'photo-1555507036-ab1f4038808a'.$q;
    if (str_contains($n, 'kaneelrol') || str_contains($n, 'kaneel'))          return $b.'photo-1694632288834-17d86b340745'.$q;
    if (str_contains($n, 'tiramisu') || str_contains($n, 'tompouce')
     || str_contains($n, 'schnitte') || str_contains($n, 'muffin')
     || str_contains($n, 'banket')   || str_contains($n, 'speculaas')
     || str_contains($n, 'vlaai')    || str_contains($n, 'cakejes'))           return $b.'photo-1534432182912-63863115e106'.$q;
    if (str_contains($n, 'chocolade') || str_contains($n, 'choco')
     || str_contains($n, 'chocolava'))                                         return $b.'photo-1679812000098-ff557c197028'.$q;
    if (str_contains($n, 'ciabatta'))                                          return $b.'photo-1667386773920-c73f3b02a3d6'.$q;
    if (str_contains($n, 'baguette'))                                          return $b.'photo-1667386773920-c73f3b02a3d6'.$q;
    if (str_contains($n, 'emmer') || str_contains($n, 'spelt'))               return $b.'photo-1559811814-e2c57b5e69df'.$q;
    if (str_contains($n, 'tijger'))                                            return $b.'photo-1598373182133-52452f7691ef'.$q;
    if (str_contains($n, 'casino'))                                            return $b.'photo-1534620808146-d33bb39128b2'.$q;
    if (str_contains($n, 'naan') || str_contains($n, 'turks')
     || str_contains($n, 'pita'))                                              return $b.'photo-1549413468-cd78edb7e75c'.$q;
    if (str_contains($n, 'rozijn') || str_contains($n, 'krent')
     || str_contains($n, 'abrikoos'))                                          return $b.'photo-1719475738774-e62c4bd01e26'.$q;
    if (str_contains($n, 'rogge'))                                             return $b.'photo-1598373182133-52452f7691ef'.$q;
    if (str_contains($n, 'zuurdesem') || str_contains($n, 'desem')
     || str_contains($n, 'volkoren') || str_contains($n, 'liefde'))           return $b.'photo-1590301157172-7ba48dd1c2b2'.$q;
    if (str_contains($n, 'bol') || str_contains($n, 'bollen')
     || str_contains($n, 'pistolet') || str_contains($n, 'petit pain')
     || str_contains($n, 'worstenbrood') || str_contains($n, 'saucijzen')
     || str_contains($n, 'hamburger') || str_contains($n, 'roombrood'))       return $b.'photo-1566698629409-787a68fc5724'.$q;
    if (str_contains($n, 'wit ') || str_contains($n, 'witte')
     || str_contains($n, 'white'))                                             return $b.'photo-1534620808146-d33bb39128b2'.$q;

    $catImages = [
        1  => $b.'photo-1534432182912-63863115e106'.$q,
        2  => $b.'photo-1590301157172-7ba48dd1c2b2'.$q,
        3  => $b.'photo-1559811814-e2c57b5e69df'.$q,
        4  => $b.'photo-1667386773920-c73f3b02a3d6'.$q,
        5  => $b.'photo-1549413468-cd78edb7e75c'.$q,
        6  => $b.'photo-1719475738774-e62c4bd01e26'.$q,
        7  => $b.'photo-1566698629409-787a68fc5724'.$q,
        8  => $b.'photo-1559811814-e2c57b5e69df'.$q,
        9  => $b.'photo-1566698629409-787a68fc5724'.$q,
        10 => $b.'photo-1534620808146-d33bb39128b2'.$q,
        11 => $b.'photo-1566698629409-787a68fc5724'.$q,
    ];
    if (isset($catImages[$categoryId])) return $catImages[$categoryId];

    return $b.'photo-1590301157172-7ba48dd1c2b2'.$q;
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
