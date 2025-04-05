<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <!-- Feuille de style -->
    <link rel="stylesheet" href="public/css/style.css">
</head>

<!-- Navigation -->
<nav>
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="produits.php">Produits</a></li>
        <li><a href="contact.php">Contact</a></li>

        <?php if (isset($_SESSION['id_utilisateur'])): ?>
            <li>Bienvenue, <?php echo htmlspecialchars($_SESSION['nom']); ?> !</li>
            <li><a href="index.php?url=logout">Se déconnecter</a></li>
        <?php else: ?>
            <li><a href="index.php?url=login">Connexion</a></li>
            <li><a href="index.php?url=inscription">Inscription</a></li>

        <?php endif; ?>
    </ul>
</nav>


<body>
    <h1>Bienvenue sur le site de figurines</h1>
    <p>Découvrez nos figurines imprimées en 3D et peintes à la main.</p>

    <h2>Nos derniers articles</h2>

    <?php if (count($articles) > 0): ?>
        <div class="articles">
            <?php foreach ($articles as $article): ?>
                <div class="article">
                    <img class="image-article" src="public/images/<?php echo htmlspecialchars($article['image']); ?>" alt="Image de l'article">
                    <h3><?php echo htmlspecialchars($article['titre']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars(substr($article['description'], 0, 150))); ?>...</p> <!-- Affiche les 150 premiers caractères de la description -->
                    <a href="index.php?url=article/<?php echo $article['id_article']; ?>" class="btn">Voir plus</a>
                </div>
            <?php endforeach; ?>
        </div>

        <a href="index.php?url=articles" class="btn">Voir les autres articles</a>
    <?php else: ?>
        <p>Aucun article disponible pour le moment.</p>
    <?php endif; ?>


    <nav>
        <ul>
            <li><a href="index.php?url=produits">Voir les produits</a></li>
        </ul>
    </nav>
</body>

</html>