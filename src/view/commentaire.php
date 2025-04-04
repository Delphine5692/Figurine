<?php

echo "<h2>Commentaires</h2>";

// Afficher les commentaires
foreach ($commentaires as $commentaire) {
    echo "<p><strong>Utilisateur {$commentaire['id_utilisateur']}</strong> a commenté le {$commentaire['date_commentaire']} : {$commentaire['msg_blog']}</p>";
}

// Formulaire d'ajout de commentaire
if (isset($_SESSION['id_utilisateur'])) { // Vérifie si l'utilisateur est connecté
    ?>
    <form action="/commentaire/ajouter" method="POST">
        <textarea name="msg_blog" placeholder="Écrivez votre commentaire..." required></textarea>
        <input type="hidden" name="id_article" value="<?php echo $_GET['id']; ?>" /> <!-- ID de l'article -->
        <button type="submit">Poster</button>
    </form>
    <?php
} else {
    echo "<p>Vous devez être connecté pour poster un commentaire.</p>";
}
?>
