<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';
render_header('Gegevens wijzigen');

if (!is_admin() && !is_client()) {
    echo '<main><h2>Geen toegang</h2><p>Log eerst in als klant of beheerder.</p>'
       . '<p><a href="login.php">Login</a> | <a href="index.php">Home</a></p></main>';
    render_footer();
    exit();
}

if (is_admin()) {
    $id = (int)($_POST['client_id'] ?? 0);
    if ($id > 0) {
        $_SESSION['update_client_id'] = $id;
    }
    $id = (int)($_SESSION['update_client_id'] ?? 0);
    $cancelUrl = 'cli-crud-get.php';
} else {
    $id = (int)$_SESSION['welkNummerIsDit'];
    $_SESSION['update_client_id'] = $id;
    $cancelUrl = 'index.php';
}

if ($id <= 0) {
    echo '<main><p>Geen klant geselecteerd.</p><p><a href="' . h($cancelUrl) . '">Terug</a></p></main>';
    render_footer();
    exit();
}

$stmt = $db->prepare('SELECT * FROM client WHERE id = :id');
$stmt->execute([':id' => $id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    echo '<main><p>Klant niet gevonden.</p><p><a href="' . h($cancelUrl) . '">Terug</a></p></main>';
    render_footer();
    exit();
}

if (isset($_SESSION['client_errors'])) {
    echo '<ul>';
    foreach ($_SESSION['client_errors'] as $error) {
        echo '<li>' . h($error) . '</li>';
    }
    echo '</ul>';
    unset($_SESSION['client_errors']);
    if (isset($_SESSION['old_client'])) {
        $client = array_merge($client, $_SESSION['old_client']);
        unset($_SESSION['old_client']);
    }
}
?>
<main class="centering">
    <h2>Gegevens wijzigen</h2>
    <form action="cli-crud-upd01.php" method="post" class="tabledisp">
        <?php client_form_fields($db, $client, false); ?>
        <p>
            <button type="submit" formaction="<?php echo h($cancelUrl); ?>">Annuleren</button>
            <input type="submit" name="client_update" value="Opslaan">
        </p>
    </form>
</main>
<?php render_footer(); ?>
