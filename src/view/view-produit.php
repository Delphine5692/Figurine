<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Produits</title>
    <!-- Feuille de style -->
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>

    <h1>Liste des Produits</h1>

    <?php if (count($produits) > 0): ?>
        <ul>
            <?php foreach ($produits as $produit): ?>
                <li>
                    <h2><?php echo htmlspecialchars($produit['nom']); ?></h2>
                    <!-- nl2br pour faire un saut à la ligne -->
                    <p>Description : <?php echo nl2br(htmlspecialchars($produit['description'])); ?></p>
                    <img class="image-produit" src="public/images/<?php echo htmlspecialchars($produit['image_1']); ?>" alt="Image 1 du produit">
                    
                    <!-- Affichage de l'image 2 si elle existe -->
                    <?php if (!empty($produit['image_2'])): ?>
                        <img src="public/images/<?php echo htmlspecialchars($produit['image_2']); ?>" alt="Image 2 du produit" />
                    <?php endif; ?>

                    <!-- Affichage de l'image 3 si elle existe -->
                    <?php if (!empty($produit['image_3'])): ?>
                        <img src="public/images/<?php echo htmlspecialchars($produit['image_3']); ?>" alt="Image 3 du produit" />
                    <?php endif; ?>
                    
                    <p>Prix : <?php echo htmlspecialchars($produit['prix']); ?> €</p>
                    <p>Taille : <?php echo htmlspecialchars($produit['taille']); ?> cm</p>
                    <p><strong>Date de publication :</strong> <?php echo $produit['date_produit']; ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucun produits disponible.</p>
    <?php endif; ?>

</body>

</html>