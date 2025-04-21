<?php

$title = "Erreur 404"; // Titre de la page

ob_start();
?>

<h1>Erreur 404</h1>
<p>Désolé, la page demandée n'existe pas.</p>
<a href="">Retour à l'accueil</a>

<?php
$content = ob_get_clean();
require SRC_DIR . '/view/layout.php';
?>