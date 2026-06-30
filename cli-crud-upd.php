<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';

// Admin can edit any client (passed via POST from cli-crud-get.php)
// Client can only edit own data (ID from session)
if (is_admin()) {
    $id = (int)($_POST['client_id'] ?? $_GET['id'] ?? 0);
    if ($id <= 0) {
        header('Location: cli-crud-get.php');
        exit();
    }
    $_SESSION['upd_from_admin'] = true;
} else {
    require_client();
    $id = (int)($_SESSION['welkNummerIsDit'] ?? 0);
    $_SESSION['upd_from_admin'] = false;
}

$_SESSION['upd_client_id'] = $id;

$stmt = $db->prepare('SELECT * FROM client WHERE id = :id');
$stmt->execute([':id' => $id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$client) {
    header('Location: index.php');
    exit();
}

if (isset($_SESSION['client_errors'])) {
    $errors = $_SESSION['client_errors'];
    unset($_SESSION['client_errors']);
    if (!empty($_SESSION['old_client'])) {
        $client = array_merge($client, $_SESSION['old_client']);
        unset($_SESSION['old_client']);
    }
    render_header('Gegevens wijzigen');
    echo '<main class="centering"><h2>Gegevens wijzigen</h2><ul>';
    foreach ($errors as $e) { echo '<li style="color:red">' . h($e) . '</li>'; }
    echo '</ul>';
} else {
    render_header('Gegevens wijzigen');
    echo '<main class="centering"><h2>Gegevens wijzigen</h2>';
}
?>
    <form action="cli-crud-upd01.php" method="post" class="tabledisp">
        <input type="hidden" name="client_id" value="<?php echo h($id); ?>">
        <?php client_form_fields($db, $client, false); ?>
        <p>
            <?php if (is_admin()): ?>
                <button type="submit" formaction="cli-crud-get.php">Breek af</button>
            <?php else: ?>
                <button type="submit" formaction="index.php">Breek af</button>
            <?php endif; ?>
            <input type="submit" value="Verder">
        </p>
    </form>
</main>
<?php render_footer(); ?>
