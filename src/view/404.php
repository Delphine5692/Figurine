<?php

$title = "Erreur 404 - Page non trouvée";
$description = "La page demandée n'existe pas. Retournez à l'accueil pour continuer.";

ob_start();
?>

<header>
    <!-- Titre caché pour l'accessibilité -->
    <h1 class="visually-hidden">Erreur 404 - Page non trouvée</h1>
</header>
<section class="error-content">
    <!-- Affichage de l'image d'erreur -->
    <img src="public/images/404.jpg" alt="Illustration d'erreur 404" class="error-image">
    <!-- Bouton de retour à l'accueil -->
    <a href="home" class="btn" aria-label="Retour à l'accueil">Retour à l'accueil</a>
</section>

<?php
$content = ob_get_clean();
require SRC_DIR . '/view/layout.php';
?>