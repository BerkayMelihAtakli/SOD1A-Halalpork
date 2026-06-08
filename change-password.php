<?php
ob_start();
session_start();
require_once 'dbconnect.php';
require_once 'product_helpers.php';

require_client();

$errors = [];

$stmt = $db->prepare("SELECT id, first_name, last_name, pswrd FROM client WHERE id = ? AND isadmin = 'N'");
$stmt->execute([(int)$_SESSION['welkNummerIsDit']]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    render_header('Wachtwoord wijzigen');
    echo '<main><h2>Geen toegang</h2><p>Alleen een ingelogde klant kan zijn wachtwoord wijzigen.</p><p><a href="login.php">Naar login</a></p></main>';
    render_footer();
    ob_end_flush();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $newPasswordRepeat = $_POST['new_password_repeat'] ?? '';

    if ($currentPassword === '' || $newPassword === '' || $newPasswordRepeat === '') {
        $errors[] = 'Vul alle velden in.';
    }

    if ($currentPassword !== '' && !password_verify($currentPassword, $client['pswrd'])) {
        $errors[] = 'Huidig wachtwoord is onjuist.';
    }

    if ($newPassword !== $newPasswordRepeat) {
        $errors[] = 'Nieuwe wachtwoorden zijn niet gelijk.';
    }

    if (strlen($newPassword) < 6) {
        $errors[] = 'Nieuw wachtwoord moet minimaal 6 tekens hebben.';
    }

    if (empty($errors)) {
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);

        $update = $db->prepare("UPDATE client SET pswrd = ? WHERE id = ?");
        $update->execute([$newHash, (int)$client['id']]);

        header('Location: index.php?msg=' . urlencode('Wachtwoord succesvol gewijzigd.'));
        exit();
    }
}

render_header('Wachtwoord wijzigen');
?>
<main class="centering">
    <h2>Wachtwoord wijzigen</h2>
    <p>Ingelogde klant: <?php echo h(trim($client['first_name'] . ' ' . $client['last_name'])); ?></p>

    <?php
    if (!empty($errors)) {
        echo '<ul>';
        foreach ($errors as $error) {
            echo '<li>' . h($error) . '</li>';
        }
        echo '</ul>';
    }
    ?>

    <form action="change-password.php" method="post" class="tabledisp">
        <label>Huidig wachtwoord</label>
        <input type="password" name="current_password" required>

        <label>Nieuw wachtwoord</label>
        <input type="password" name="new_password" required>

        <label>Herhaal nieuw wachtwoord</label>
        <input type="password" name="new_password_repeat" required>

        <p>
            <button type="submit" formaction="index.php">Breek af</button>
            <input type="submit" value="Wachtwoord wijzigen">
        </p>
    </form>
</main>
<?php
render_footer();
ob_end_flush();
?>
