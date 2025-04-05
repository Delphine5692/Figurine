<?php

echo "<h2>Commentaires</h2>";

// Afficher les commentaires
foreach ($commentaires as $commentaire) {
    echo "<p><strong>Utilisateur {$commentaire['id_utilisateur']}</strong> a commenté le {$commentaire['date_commentaire']} : {$commentaire['msg_blog']}</p>";
}

// Formulaire d'ajout de commentaire
if (isset($_SESSION['id_utilisateur'])) { // Vérifie si l'utilisateur est connecté
    ?>
<form action="/web/Figurine/index.php?url=commentaire" method="POST">
    <input type="hidden" name="id_article" value="<?= $id_article; ?>">
    <textarea name="msg_blog" placeholder="Votre commentaire" required></textarea>
    <button type="submit">Ajouter un commentaire</button>
</form>
    <?php
} else {
    echo "<p>Vous devez être connecté pour poster un commentaire.</p>";
}
?>