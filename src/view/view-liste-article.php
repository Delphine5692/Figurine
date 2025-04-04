<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Articles</title>
    <!-- Feuille de style -->
    <link rel="stylesheet" href="public/css/style.css">

</head>

<body>

    <h1>Liste des Articles</h1>

    <div class="articles">
        <?php if (count($articles) > 0): ?>
            <?php foreach ($articles as $article): ?>
                <div class="article">
                    <h2><?php echo htmlspecialchars($article['titre']); ?></h2>
                    <img src="public/images/<?php echo htmlspecialchars($article['image']); ?>" alt="Image de l'article">
                    <p><?php echo nl2br(htmlspecialchars(substr($article['description'], 0, 100))); ?>...</p>
                    <a href="index.php?url=article/<?php echo $article['id_article']; ?>" class="btn">Voir plus</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun article disponible.</p>
        <?php endif; ?>
    </div>

    <!-- Bouton retour à l'accueil -->
    <a href="index.php" class="back-btn">Retour à l'accueil</a>
    

</body>

</html>
