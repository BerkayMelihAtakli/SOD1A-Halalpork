<?php
session_start();
require_once 'category_country_helpers.php';
render_header('Categorie toevoegen');
require_admin();
?>
<main class="centering">
    <h2>Categorie toevoegen</h2>
    <?php if (isset($_GET['error'])) { echo '<p><strong>' . h($_GET['error']) . '</strong></p>'; } ?>
    <form action="cat-crud-adding.php" method="post">
        <label>Naam</label>
        <input type="text" name="name" required pattern="[A-Za-zÀ-ÿ ]+">
        <p><button type="submit">Sla op</button> <button type="submit" formaction="cat-crud-get.php" formnovalidate>Breek af</button></p>
    </form>
</main>
<?php render_footer(); ?>
