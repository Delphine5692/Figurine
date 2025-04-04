<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'Article</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>

    <h1><?php echo htmlspecialchars($article['titre']); ?></h1>
    <img class="image-article" src="public/images/<?php echo htmlspecialchars($article['image']); ?>" alt="Image de l'article">
    <p><?php echo nl2br(htmlspecialchars($article['description'])); ?></p>
    <p><strong>Date de publication :</strong> <?php echo $article['date_publication']; ?></p>

    <h2>Commentaires</h2>
    <?php if (count($commentaires) > 0): ?>
        <ul>
            <?php foreach ($commentaires as $commentaire): ?>
                <li>
                    <p><strong><?php echo htmlspecialchars($commentaire['nom']) . ' ' . htmlspecialchars($commentaire['prenom']); ?> :</strong></p>
                    <p><?php echo nl2br(htmlspecialchars($commentaire['msg_blog'])); ?></p>
                    <p><em>Publié le : <?php echo $commentaire['date_commentaire']; ?></em></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucun commentaire pour cet article.</p>
    <?php endif; ?>

    <!-- Formulaire pour ajouter un commentaire -->
    <?php if (isset($_SESSION['id_utilisateur'])): ?>
        <h3>Ajouter un commentaire</h3>
        <form action="index.php?url=commentaire&id_article=<?php echo $article['id_article']; ?>" method="post">
            <textarea name="msg_blog" rows="4" cols="50" placeholder="Votre commentaire"></textarea><br>
            <button type="submit">Poster le commentaire</button>
        </form>
    <?php else: ?>
        <p>Vous devez être connecté pour laisser un commentaire.</p>
    <?php endif; ?>


    <!-- Ajouter un bouton pour revenir à la liste des articles -->
    <a href="index.php?url=articles" class="btn">Retour à la liste des articles</a>
        <!-- Bouton retour à l'accueil -->
        <a href="index.php" class="back-btn">Retour à l'accueil</a>

</body>

</html>