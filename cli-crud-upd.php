<?php
session_start();
require_once 'dbconnect.php';
require_once 'client_helpers.php';
render_header('Klant wijzigen');
require_admin();

$id = (int)($_POST['client_id'] ?? $_GET['id'] ?? 0);
if ($id <= 0) {
    echo '<main><p>Geen klant gekozen.</p><p><a href="cli-crud-get.php">Terug</a></p></main>';
    render_footer();
    exit();
}
$_SESSION['update_client_id'] = $id;

$stmt = $db->prepare('SELECT * FROM client WHERE id = :id');
$stmt->execute([':id' => $id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$client) {
    echo '<main><p>Klant niet gevonden.</p><p><a href="cli-crud-get.php">Terug</a></p></main>';
    render_footer();
    exit();
}
?>
<main class="centering">
    <h2>Klant wijzigen</h2>
    <?php
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
    <form action="cli-crud-update.php" method="post" class="tabledisp">
        <label>ID</label><input type="number" name="client_id" value="<?php echo h($client['id']); ?>" readonly>
        <?php client_form_fields($db, $client, true); ?>
        <p>
            <button type="submit" formaction="cli-crud-get.php">Breek af</button>
            <input type="submit" name="client_update" value="Opslaan">
        </p>
    </form>
</main>
<?php render_footer(); ?>
