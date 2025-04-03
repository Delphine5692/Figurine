<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Articles</title>
    <link rel="stylesheet" href="/Figurine/public/css/style.css">

</head>

<body>

    <h1>Liste des Articles</h1>

    <?php if (count($articles) > 0): ?>
        <ul>
            <?php foreach ($articles as $article): ?>
                <li>
                    <h2><?php echo htmlspecialchars($article['titre']); ?></h2>
                    <!-- nl2br pour faire un saut à la ligne -->
                    <p>Description : <?php echo nl2br(htmlspecialchars($article['description'])); ?></p>
                    <img class="image-produit" src="/Figurine/public/images/<?php echo htmlspecialchars($article['image']); ?>" alt="Image de l'article">
                    <p><strong>Date de publication :</strong> <?php echo $article['date_publication']; ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucun article disponible.</p>
    <?php endif; ?>

</body>

</html>