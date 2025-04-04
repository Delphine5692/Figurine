<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['titre']); ?></title>
    <!-- Feuille de style -->
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>
    <h1><?php echo htmlspecialchars($article['titre']); ?></h1>
    <img class="image-article" src="public/images/<?php echo htmlspecialchars($article['image']); ?>" alt="Image de l'article">
    <p><strong>Description :</strong> <?php echo nl2br(htmlspecialchars($article['description'])); ?></p>
    <p><strong>Date de publication :</strong> <?php echo $article['date_publication']; ?></p>

    <a href="index.php" class="btn">Retour à l'accueil</a>
    <!-- Bouton retour à la liste des articles -->
    <a href="index.php?url=articles" class="back-btn">Retour à la liste des articles</a>
</body>

</html>
