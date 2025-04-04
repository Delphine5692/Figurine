<?php

namespace Figurine\Controller;

use Figurine\Model\Produit;
use Figurine\Model\Article;

class AccueilController
{
    public function afficherAccueil()
    {

        $articles = Article::getAllArticles();
        $produits = Produit::getAllProduits();

        // Récupérer les 3 derniers articles triés par date de publication
        $articles = Article::getLastThreeArticles();

        if ($articles === null) {
            header('Location: /view-404.php'); // Si aucun article n'est trouvé
            exit();
        }


        require_once 'src/view/view-accueil.php';
    }
}
